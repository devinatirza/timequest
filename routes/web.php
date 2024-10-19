<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserImageController;
use App\Http\Requests\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
});

use App\Http\Controllers\ProductController;

Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');
Route::post('/wishlist/{productId}', [ProductController::class, 'toggleWishlist'])->name('wishlist.toggle');

Route::get('/about', function () {
    return view('about');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::middleware(['throttle:6,1'])->group(function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/register', [RegisteredUserController::class, 'store']);
    });

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'verifyKBAAnswers'])->name('password.kba.verify');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});


Route::get('/profile', function () {
    if (Auth::check()) {
        return redirect()->route('profile');
    }
    return redirect()->route('login');
})->name('profile');


require __DIR__.'/auth.php';