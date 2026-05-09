<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
        $already = Favourite::where('user_id', Auth::id())
            ->where('recipe_id', $recipe->id)
            ->exists();

        if ($already) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Already in favourites!'], 409);
            }
            return back()->with('error', 'Recipe is already in your favourites!');
        }

        Favourite::create([
            'user_id' => Auth::id(),
            'recipe_id' => $recipe->id,
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Recipe added to favourites!']);
        }

        return back()->with('success', 'Recipe added to favourites!');
    }

    // Remove a recipe from favourites
    public function destroy(Recipe $recipe)
    {
        Favourite::where('user_id', Auth::id())
            ->where('recipe_id', $recipe->id)
            ->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Recipe removed from favourites!'
            ]);
        }

        return back()->with('success', 'Recipe removed from favourites!');
    }
}
