<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RecipeController;
use App\Http\Controllers\User\FavouriteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IngredientController;
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
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->prefix('admin')->group(function () {

        // ---- Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Recipes CRUD
        Route::get('/recipes', [RecipeController::class, 'index'])->name('admin.recipes.index');
        Route::get('/recipes/create', [RecipeController::class, 'create'])->name('admin.recipes.create');
        Route::post('/recipes', [RecipeController::class, 'store'])->name('admin.recipes.store');
        Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('admin.recipes.edit');
        Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('admin.recipes.update');
        Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('admin.recipes.destroy');

        // ---- Ingredients CRUD
        Route::get('/ingredients', [IngredientController::class, 'index'])->name('admin.ingredients.index');
        Route::get('/ingredients/create', [IngredientController::class, 'create'])->name('admin.ingredients.create');
        Route::post('/ingredients', [IngredientController::class, 'store'])->name('admin.ingredients.store');
        Route::get('/ingredients/{ingredient}/edit', [IngredientController::class, 'edit'])->name('admin.ingredients.edit');
        Route::put('/ingredients/{ingredient}', [IngredientController::class, 'update'])->name('admin.ingredients.update');
        Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('admin.ingredients.destroy');

        // Extra AJAX helpers
        Route::post('/ingredients/check-name', [IngredientController::class, 'checkName'])->name('admin.ingredients.checkName');
        Route::get('/ingredients/search', [IngredientController::class, 'search'])->name('admin.ingredients.search');
    });


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
