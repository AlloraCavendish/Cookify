<?php

namespace App\Http\Controllers\User;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
        public function dashboard()
    {
        return view('user.dashboard');
    }
    
    // Show saved recipes
    public function favourites()
    {
        $recipes = Auth::user()->favourites;
        return view('user.favourites.index', compact('recipes'));
    }

    // Save recipe
    public function saveRecipe($id)
    {
        $recipe = Recipe::findOrFail($id);
        Auth::user()->favourites()->attach($recipe->id);

        return back()->with('success', 'Recipe saved!');
    }

    // Remove recipe from favourites
    public function removeRecipe($id)
    {
        Auth::user()->favourites()->detach($id);
        return back()->with('success', 'Recipe removed!');
    }
}
