<?php

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserImageController;
use App\Http\Requests\Auth\LoginController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('home');
})->name("home");

Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');


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


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
});


// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::resource('products', AdminProductController::class);
// });

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminProductController::class, 'index'])
            ->name('dashboard');
            
            Route::get('/create', [AdminProductController::class, 'create'])
            ->name('products.create');
            
        Route::post('/products', [AdminProductController::class, 'store'])
            ->name('products.store');
            
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])
            ->name('products.edit');
            
        Route::put('/products/{product}', [AdminProductController::class, 'update'])
            ->name('products.update');
            
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])
            ->name('products.destroy');
});

Route::fallback(function () {
    if (request()->is('admin/*')) {
        return redirect()->route('login')
            ->with('error', 'Unauthorized access.');
    }
    return abort(404);
});

require __DIR__.'/auth.php';