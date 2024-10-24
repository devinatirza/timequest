<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('view');
        $this->middleware('throttle:60,1');
    }

    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with(['product' => function($query) {
                $query->select('id', 'name', 'price', 'image_path', 'brand_id');
            }])
            ->get();

        return view('wishlist.view', compact('wishlist'));
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => ['required', 'exists:products,id'],
            ]);

            $product = Product::findOrFail($validated['product_id']);
            
            $existingWishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($existingWishlist) {
                return response()->json([
                    'message' => 'Product already in wishlist'
                ], Response::HTTP_BAD_REQUEST);
            }

            $wishlist = Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'created_at' => now(),
            ]);

            return response()->json([
                'message' => 'Product added to wishlist successfully',
                'wishlist_id' => $wishlist->id
            ], Response::HTTP_CREATED);

        } catch (QueryException $e) {
            report($e);
            return response()->json([
                'message' => 'Failed to add product to wishlist'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function remove($productId)
    {
        try {
            $wishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if (!$wishlist) {
                return response()->json([
                    'message' => 'Wishlist item not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $wishlist->delete();

            return response()->json([
                'message' => 'Product removed from wishlist successfully'
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            report($e);
            return response()->json([
                'message' => $e
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function toggle(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => ['required', 'exists:products,id'],
            ]);

            $existingWishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($existingWishlist) {
                $existingWishlist->delete();
                $message = 'Product removed from wishlist';
                $status = Response::HTTP_OK;
            } else {
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'product_id' => $validated['product_id'],
                    'created_at' => now(),
                ]);
                $message = 'Product added to wishlist';
                $status = Response::HTTP_CREATED;
            }

            return response()->json([
                'message' => $message
            ], $status);

        } catch (QueryException $e) {
            report($e);
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}