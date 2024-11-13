<?php

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserImageController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:8,1'])->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::middleware(['throttle:6,1'])->group(function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/register', [RegisteredUserController::class, 'store']);
    });

    Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])
        ->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'verifyKBAAnswers'])
        ->name('password.kba.verify');

    Route::get('reset-password', [ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'reset'])
        ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/user-image/{userId}/{filename}', [UserImageController::class, 'serveImage'])
    ->name('user.image')
    ->where('userId', '[0-9a-f\-]+');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.change');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminProductController::class, 'index'])
            ->name('dashboard');
            
        Route::get('/create', [AdminProductController::class, 'create'])
            ->middleware('throttle:10,1')
            ->name('products.create');
            
        Route::post('/products', [AdminProductController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('products.store');
            
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])
            ->middleware('throttle:10,1')
            ->name('products.edit');
            
        Route::put('/products/{product}', [AdminProductController::class, 'update'])
            ->middleware('throttle:10,1')
            ->name('products.update');
            
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])
            ->middleware('throttle:10,1')    
            ->name('products.destroy');
});