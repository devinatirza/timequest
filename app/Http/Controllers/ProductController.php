<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'nullable|exists:brands,id',
            'search' => 'nullable|string|max:255'
        ]);
    
        $products = Product::with('brand')
            ->when($validated['brand'] ?? null, function ($query, $brand) {
                $query->where('brand_id', $brand);
            })
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->get();
    
        $brands = Brand::all();
    
        return view('catalog', compact('products', 'brands'));
    }
    
    public function fetchUpdates(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'nullable|exists:brands,id',
            'search' => 'nullable|string|max:255'
        ]);
    
        $products = Product::with('brand')
            ->when($validated['brand'] ?? null, function ($query, $brand) {
                $query->where('brand_id', $brand);
            })
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->get();
    
        return response()->json($products);
    }    

    public function toggleWishlist(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error','');
        }

        $user = Auth::user();
        $product = Product::findOrFail($productId);

        if ($user->wishlists()->where('product_id', $productId)->exists()) {
            $user->wishlists()->detach($productId);
            $message = 'Product removed from wishlist.';
        } else {
            $user->wishlists()->attach($product);
            $message = 'Product added to wishlist.';
        }

        return response()->json(['message' => $message]);
    }
}
