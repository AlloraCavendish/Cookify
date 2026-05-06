<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    // Show user's favourites
    public function index()
    {
        $favourites = Favourite::with('recipe')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.favourites.index', compact('favourites'));
    }

    // Save a recipe to favourites
    public function store(Recipe $recipe)
    {
        Favourite::firstOrCreate([
            'user_id' => Auth::id(),
            'recipe_id' => $recipe->id,
        ]);

        return back()->with('success', 'Recipe added to favourites!');
    }

    // Remove a recipe from favourites
    public function destroy(Recipe $recipe)
    {
        Favourite::where('user_id', Auth::id())
            ->where('recipe_id', $recipe->id)
            ->delete();

        return back()->with('success', 'Recipe removed from favourites!');
    }
}
