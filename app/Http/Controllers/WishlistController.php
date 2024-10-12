<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view(Wishlist $wishlist)
    {
        try {
            $this->authorize('view', $wishlist);
        } catch (AuthorizationException $e) {
            abort(403, $e->getMessage());
        }

        return view('wishlist.view', compact('wishlist'));
    }

    public function add(Wishlist $wishlist)
    {
        try {
            $this->authorize('update', $wishlist);
        } catch (AuthorizationException $e) {
            abort(403, $e->getMessage());
        }

        $validated = $wishlist->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
        ]);

        return redirect()->back()->with('success','Product added to wishlist.');
    }

    public function remove(Wishlist $wishlist)
    {
        try {
            $this->authorize('delete', $wishlist);
        } catch (AuthorizationException $e) {
            abort(403, $e->getMessage());
        }
        
        $validated = $wishlist->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::where('user_id', Auth::id())
                ->where('product_id', $validated['product_id'])
                ->delete();

        return redirect()->back()->with('success', 'Product removed from wishlist.');
    }

}
