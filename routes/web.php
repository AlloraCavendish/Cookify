<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\FavouriteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// 🔹 Homepage (public)
Route::get('/', function () {
    return view('main'); // 👉 resources/views/main.blade.php
})->name('home');

// 🔹 Auth pages
Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); // 👉 resources/views/auth/login.blade.php
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
// 5 attempts per 1 minute

Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); // 👉 resources/views/auth/register.blade.php
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔹 Public Search
Route::get('/search', function () {
    return view('main'); // 👉 maybe replace with resources/views/search/index.blade.php later
})->name('search');

Route::get('/forgot-password', [PasswordResetController::class, 'show'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'send'])->name('password.email')->middleware('throttle:3,1');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('user.dashboard')->with('success', 'Email verified!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::prefix('user')->middleware(['verified'])->group(function () {
    // all your user routes stay the same
    });

    // 🔹 Common dashboard redirect
    Route::get('/dashboard', function () {
        if (auth()->user()->is_admin) {
            return redirect('/admin');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->group(function () {

    // ---- Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');

    // ---- Recipes actions
    Route::prefix('recipes')->group(function () {
        Route::post('/{id}/save', [UserController::class, 'saveRecipe'])->name('user.recipes.save');
        Route::delete('/{id}/remove', [UserController::class, 'removeRecipe'])->name('user.recipes.remove');
        Route::get('/{id}/share', [UserController::class, 'share'])->name('user.recipes.share');
    });

    // ---- Favourites
    Route::get('/favourites', [FavouriteController::class, 'index'])->name('user.favourites.index');
    Route::post('/favourites/{recipe}', [FavouriteController::class, 'store'])->name('user.favourites.store');
    Route::delete('/favourites/{recipe}', [FavouriteController::class, 'destroy'])->name('user.favourites.destroy');
    });
});
