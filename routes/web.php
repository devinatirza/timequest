<?php

use App\Http\Controllers\WishlistController;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/contact', function () {
    return view('contact');
});
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::post('/wishlist/add', [WishlistController::class, 'add']);
    Route::post('/wishlist/remove', [WishlistController::class, 'remove']);
});
Route::get('/users/{user}/wishlists/{wishlist}', function (User $user, Wishlist $wishlist) {
    return $wishlist;
})->scopeBindings();