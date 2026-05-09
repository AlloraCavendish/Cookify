<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\FavouriteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserController;

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
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); // 👉 resources/views/auth/register.blade.php
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔹 Public Search
Route::get('/search', function () {
    return view('main'); // 👉 maybe replace with resources/views/search/index.blade.php later
})->name('search');


/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

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
