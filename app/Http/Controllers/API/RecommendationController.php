<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Recipe;

class RecommendationController extends Controller
{
    public function getRecommendations(Request $request)
    {
        // 1️⃣ Validate input
        $request->validate([
            'ingredients'   => 'required',
            'page'          => 'integer|min:1',
            'max_prep_time' => 'nullable|integer|min:1',
            'cuisine'       => 'nullable|string|max:100',
        ]);

        // 2️⃣ Parse ingredients
        $ingredientsInput = $request->input('ingredients', []);

        if (is_string($ingredientsInput)) {
            $ingredientsInput = explode(',', $ingredientsInput);
        }

        $inputIngredients = collect($ingredientsInput)
            ->filter(fn($i) => is_string($i))
            ->map(fn($i) => strtolower(trim($i)))
            ->filter(fn($i) => $i !== '')
            ->unique()
            ->values()
            ->toArray();

        if (count($inputIngredients) === 0) {
            return response()->json(['error' => 'No valid ingredients provided.'], 422);
        }

        // 3️⃣ Pagination
        $page    = max(1, (int) $request->input('page', 1));
        $perPage = 10;

        // 4️⃣ Cache key based on filters + ingredients
        $cacheKey = 'recommendations:' . md5(implode(',', $inputIngredients) . $request->input('cuisine') . $request->input('max_prep_time'));

        // 5️⃣ Fetch all recipes with ingredients (cached for 10 mins)
        $allRecipes = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request) {
            return Recipe::query()
                ->when($request->filled('cuisine'), fn($q) => 
                    $q->whereRaw('LOWER(cuisine) = ?', [strtolower($request->input('cuisine'))])
                )
                ->when($request->filled('max_prep_time'), fn($q) => 
                    $q->where('prep_time', '<=', (int) $request->input('max_prep_time'))
                )
                ->with('ingredients')
                ->get();
        });

        // 6️⃣ Match recipes against input ingredients
        $ready     = [];
        $suggested = [];

        foreach ($allRecipes as $recipe) {
            $missingMain     = [];
            $missingOptional = [];

            foreach ($recipe->ingredients as $ing) {
                $name = strtolower(trim($ing->name));
                $type = $ing->type ?? 'main';

                if (!in_array($name, $inputIngredients)) {
                    if ($type === 'main') {
                        $missingMain[] = $ing->name;
                    } else {
                        $missingOptional[] = $ing->name;
                    }
                }
            }

            if (empty($missingMain)) {
                $ready[] = [
                    'id'    => $recipe->id,
                    'title' => $recipe->title,
                ];
            } else {
                $suggested[] = [
                    'id'               => $recipe->id,
                    'title'            => $recipe->title,
                    'missing_main'     => $missingMain,
                    'missing_optional' => $missingOptional,
                    'missing_count'    => count($missingMain),
                ];
            }
        }

        // 7️⃣ Sort suggested by fewest missing main ingredients
        usort($suggested, fn($a, $b) => $a['missing_count'] <=> $b['missing_count']);

        // 8️⃣ Remove helper key before returning
        $suggested = array_map(function ($item) {
            unset($item['missing_count']);
            return $item;
        }, $suggested);

        // 9️⃣ Paginate ready and suggested separately
        $readyTotal     = count($ready);
        $suggestedTotal = count($suggested);
        $totalPages     = max(1, ceil(($readyTotal + $suggestedTotal) / $perPage));
        $start          = ($page - 1) * $perPage;

        $paginatedReady     = array_slice($ready, $start, $perPage);
        $remaining          = $perPage - count($paginatedReady);
        $paginatedSuggested = $remaining > 0 ? array_slice($suggested, 0, $remaining) : [];

        return response()->json([
            'ready'       => $paginatedReady,
            'suggested'   => $paginatedSuggested,
            'page'        => $page,
            'total_pages' => $totalPages,
            'meta'        => [
                'total_ready'     => $readyTotal,
                'total_suggested' => $suggestedTotal,
                'per_page'        => $perPage,
            ]
        ]);
    }
}