<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    public function getRecommendations(Request $request)
    {
        // 1️⃣ Handle ingredients input
        $ingredientsInput = $request->input('ingredients', []);

        // If it's a string like "egg,tomato"
        if (is_string($ingredientsInput)) {
            $ingredientsInput = explode(',', $ingredientsInput);
        }

        // Ensure it's an array of clean strings
        $inputIngredients = collect($ingredientsInput)
            ->filter(fn($i) => is_string($i)) // skip arrays/null
            ->map(fn($i) => strtolower(trim($i)))
            ->unique()
            ->values()
            ->toArray();

        if (count($inputIngredients) === 0) {
            return response()->json(['error' => 'No valid ingredients provided.'], 422);
        }

        // 2️⃣ Pagination (optional)
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 10;

        // 3️⃣ Filtering (optional)
        $query = DB::table('recipes');

        if ($request->has('cuisine')) {
            $query->where('cuisine', $request->input('cuisine'));
        }
        if ($request->has('max_prep_time')) {
            $query->where('prep_time', '<=', (int) $request->input('max_prep_time'));
        }

        $allRecipes = $query
            ->leftJoin('recipe_ingredients', 'recipes.id', '=', 'recipe_ingredients.recipe_id')
            ->leftJoin('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.id')
            ->select(
                'recipes.id as recipe_id',
                'recipes.title as recipe_title',
                'ingredients.name as ingredient_name',
                'ingredients.type as ingredient_type'
            )
            ->get()
            ->groupBy('recipe_id');

        $ready = [];
        $suggested = [];

        foreach ($allRecipes as $recipeId => $items) {
            $recipeTitle = $items[0]->recipe_title;
            $recipeIngredients = collect($items)
                ->filter(fn($i) => $i->ingredient_name)
                ->map(fn($i) => [
                    'name' => strtolower(trim($i->ingredient_name)),
                    'type' => $i->ingredient_type ?? 'main'
                ])
                ->toArray();

            $missingMain = [];
            $missingOptional = [];

            foreach ($recipeIngredients as $ing) {
                if (!in_array($ing['name'], $inputIngredients)) {
                    if ($ing['type'] === 'main') {
                        $missingMain[] = $ing['name'];
                    } else {
                        $missingOptional[] = $ing['name'];
                    }
                }
            }

            if (empty($missingMain)) {
                $ready[] = ['id' => $recipeId, 'title' => $recipeTitle];
            } else {
                $suggested[] = [
                    'id' => $recipeId, // ✅ include id
                    'title' => $recipeTitle,
                    'missing_main' => $missingMain,
                    'missing_optional' => $missingOptional
                ];
            }
        }

        // 4️⃣ Sort suggested by fewest missing main ingredients
        usort($suggested, fn($a, $b) => count($a['missing_main']) <=> count($b['missing_main']));

        // 5️⃣ Apply pagination
        $totalRecipes = count($ready) + count($suggested);
        $totalPages = ceil($totalRecipes / $perPage);
        $start = ($page - 1) * $perPage;
        $paginatedReady = array_slice($ready, $start, $perPage);
        $remaining = $perPage - count($paginatedReady);
        $paginatedSuggested = array_slice($suggested, 0, max(0, $remaining));

        return response()->json([
            'ready' => $paginatedReady,
            'suggested' => $paginatedSuggested,
            'page' => $page,
            'total_pages' => $totalPages
        ]);
    }
}
