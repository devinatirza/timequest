<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ComparisonController extends Controller
{
    public function compare(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|string|regex:/^([0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12},)*([0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12})$/'
        ]);
    
        $productIds = explode(',', $validated['products']);
        $products = Product::whereIn('id', $productIds)->get();
    
        if ($products->count() < 2 && $products->count() > 3) {
            return redirect()->route('catalog')->withErrors('Please select minimum 2 and maximum 3 products for comparison.');
        }
    
        return view('comparison', compact('products'));
    }
    
    
}
