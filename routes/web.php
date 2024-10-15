<?php

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
});

Route::get('/profile', function () {
    if (Auth::check()) {
        return redirect()->route('profile');
    }
    return redirect()->route('login');
})->name('profile');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/user-image/{userId}/{filename}', [UserImageController::class, 'serveImage'])
        ->name('user.image');

    Route::get('/profile-image/{filename}', function ($filename) {
        $path = 'profile_images/' . $filename;
        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        }
        $file = Storage::disk('private')->get($path);
        $type = Storage::disk('private')->mimeType($path);
        return response($file, 200)->header('Content-Type', $type);
    })->name('profile.image');

require __DIR__.'/auth.php';