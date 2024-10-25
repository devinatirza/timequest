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
        $query = Product::with('brand');
    
        if ($request->has('brand')) {
            $brand = htmlspecialchars(strip_tags($request->input('brand')), ENT_QUOTES, 'UTF-8');
            $query->where('brand_id', $brand);
        }
    
        if ($request->has('search')) {
            $search = htmlspecialchars(strip_tags($request->input('search')), ENT_QUOTES, 'UTF-8');
            
            $validator = Validator::make(['search' => $search], [
                'search' => 'nullable|string|max:255',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
    
            $query->where('name', 'like', '%' . $search . '%');
        }
    
        $products = $query->get();
        $brands = Brand::all();
    
        return view('catalog', compact('products', 'brands'));
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
