<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $key = 'admin-actions:' . $request->ip();
            
            if (RateLimiter::tooManyAttempts($key, 15)) { 
                Log::warning('Rate limit exceeded for admin actions', [
                    'ip' => $request->ip(),
                    'admin_id' => auth()->id(),
                    'action' => $request->route()->getName(),
                    'method' => $request->method()
                ]);
                
                $seconds = RateLimiter::availableIn($key);

                return response()->json([
                    'error' => 'Too many attempts.'
                ], 429);
            }
            
            RateLimiter::hit($key);
            return $next($request);
        });
    }

    private function checkDestructiveAction(Request $request)
    {
        $key = 'admin-destructive:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            Log::warning('Rate limit exceeded for destructive admin action', [
                'ip' => $request->ip(),
                'admin_id' => auth()->id(),
                'action' => $request->route()->getName()
            ]);
            
            $seconds = RateLimiter::availableIn($key);
            
            abort(429, 'Too many delete attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.');
        }
        
        RateLimiter::hit($key, 300); 
    }

    public function index(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $search = htmlspecialchars(strip_tags($search), ENT_QUOTES, 'UTF-8'); // Sanitize input

            $validator = Validator::make(['search' => $search], [
                'search' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $products = Product::with('brand')
                        ->when($search, function ($query, $search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        })
                        ->paginate(10);

            return view('admin.dashboard', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error loading products dashboard: ' . $e->getMessage());
            return back()->with('error', 'Failed to load products. Please try again.');
        }
    }
    
    public function create()
    {
        $brands = Brand::all();
        return view('admin.create', compact('brands'));
    }

    public function store(Request $request)
    {
        Log::info($request->brand);
        try {
            $validated = $request->validate([
                'name' => [
                    'required', 
                    'string', 
                    'min:3', 
                    'max:255',
                    'regex:/^[\pL\s\-0-9]+$/u' 
                ],
                'description' => [
                    'required', 
                    'string',
                    'min:10',
                    'max:1000'
                ],
                'price' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:999999999.99'
                ],
                'brand' => [
                    'required',
                    'exists:brands,id'
                ],
                'image' => [
                    'required',
                    'file',
                    'mimes:jpeg,png,jpg',
                    'max:2048'
                ]
            ], [
                'name.required' => 'Product name is required.',
                'name.regex' => 'Product name can only contain letters, numbers, spaces, and hyphens.',
                'description.required' => 'Product description is required.',
                'description.min' => 'Description must be at least 10 characters.',
                'price.required' => 'Product price is required.',
                'price.numeric' => 'Price must be a valid number.',
                'price.min' => 'Price cannot be negative.',
                'brand.required' => 'Brand name is required.',
                'brand.exists' => 'Brand is not registered.',
                'image.required' => 'Product image is required.',
                'image.mimes' => 'Image must be a valid type (jpeg, jpg, png).',
                'image.max' => 'Image size cannot exceed 2MB.'
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');

                try {
                    $tempPath = $uploadedFile->getPathname();
                    $imageInfo = @getimagesize($tempPath);
                    
                    if ($imageInfo === false) {
                        Log::error('Invalid image type: ' . $uploadedFile->getClientMimeType());
                        throw ValidationException::withMessages([
                            'image' => ['The uploaded file is not a valid image.']
                        ]);
                    }

                    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!in_array($imageInfo['mime'], $allowedMimeTypes)) {
                        Log::error('Invalid image type: ' . $uploadedFile->getClientMimeType());
                        throw ValidationException::withMessages([
                            'image' => ['The uploaded file type is not allowed.']
                        ]);
                    }

                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($tempPath);

                    $imageName = Str::uuid() . '.jpg';
                    $imagePath = 'images/watches/' . $imageName;

                    Storage::disk('public')->put(
                        $imagePath,
                        $image->cover(800, 800)->toJpeg(80)
                    );

                    $savedPath = Storage::disk('public')->path($imagePath);
                    if (!exif_imagetype($savedPath)) {
                        Storage::disk('public')->delete($imagePath);
                        throw ValidationException::withMessages([
                            'image' => ['The uploaded file could not be processed as an image.']
                        ]);
                    }

                } catch (ValidationException $e) {
                    throw $e;
                } catch (\Exception $e) {
                    Log::error('Image processing failed: ' . $e->getMessage());
                    throw ValidationException::withMessages([
                        'image' => ['An error occurred while processing the image.']
                    ]);
                }
            }

            $sanitized = [
                'id' => Str::uuid(),
                'name' => strip_tags($validated['name']),
                'description' => strip_tags($validated['description']),
                'price' => round(floatval($validated['price']), 2),
                'brand_id' => $validated['brand'],
                'image_path' => $imagePath
            ];

            Product::create($sanitized);

            Log::info('Product created successfully', [
                'product_id' => $sanitized['id'],
                'admin_id' => auth()->id()
            ]);

            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Product created successfully');
                
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage(), [
                'admin_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'An error occurred while creating the product. Please try again.');
        }
    }

    public function edit(Product $product)
    {
        $brands = Brand::all();
        return view('admin.edit', compact('product', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'description' => ['required', 'string', 'min:10'],
                'price' => ['required', 'numeric', 'min:0'],
                'brand_id' => ['required', 'exists:brands,id'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]);

            if ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');
                $imageName = Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();

                $manager = new ImageManager(new Driver());
                $image = $manager->read($uploadedFile);
                $image->cover(300, 300);

                $path = 'images/watches/' . $imageName;
                Storage::disk('public')->put($path, $image->toJpeg(80));

                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }

                $validated['image_path'] = $path;
            }

            $product->update($validated);

            Log::info('Product updated by admin', [
                'admin_id' => auth()->id(),
                'product_id' => $product->id
            ]);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error updating product', [
                'admin_id' => auth()->id(),
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, Product $product)
    {
        $this->checkDestructiveAction($request);

        try {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $product->delete();

            Log::info('Product deleted by admin', [
                'admin_id' => auth()->id(),
                'product_id' => $product->id,
                'ip' => $request->ip() 
            ]);

            RateLimiter::clear('admin-actions:' . request()->ip());

            return redirect()->route('admin.dashboard')
                ->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting product', [
                'admin_id' => auth()->id(),
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),  
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'An error occurred while deleting the product.']);
        }
    }
}