<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecipeIngredientsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Map recipe titles to ingredient names and quantities
        $map = [
            'Tomato Omelette' => [
                ['name' => 'egg', 'quantity' => '2', 'unit' => 'pcs'],
                ['name' => 'tomato', 'quantity' => '1', 'unit' => 'pcs'],
                ['name' => 'onion', 'quantity' => '1/4', 'unit' => 'pcs'],
                ['name' => 'salt', 'quantity' => '1/4', 'unit' => 'tsp'],
                ['name' => 'pepper', 'quantity' => '1/8', 'unit' => 'tsp'],
            ],
            'Simple Fried Rice' => [
                ['name' => 'rice', 'quantity' => '2', 'unit' => 'cups'],
                ['name' => 'egg', 'quantity' => '1', 'unit' => 'pcs'],
                ['name' => 'garlic', 'quantity' => '1', 'unit' => 'clove'],
                ['name' => 'onion', 'quantity' => '1/2', 'unit' => 'pcs'],
                ['name' => 'soy sauce', 'quantity' => '1', 'unit' => 'tbsp'],
            ],
            'Garlic Butter Chicken' => [
                ['name' => 'chicken', 'quantity' => '2', 'unit' => 'pcs'],
                ['name' => 'butter', 'quantity' => '2', 'unit' => 'tbsp'],
                ['name' => 'garlic', 'quantity' => '3', 'unit' => 'clove'],
                ['name' => 'salt', 'quantity' => '1/2', 'unit' => 'tsp'],
                ['name' => 'pepper', 'quantity' => '1/4', 'unit' => 'tsp'],
            ],
        ];

        foreach ($map as $recipeTitle => $ings) {
            $recipe = DB::table('recipes')->where('title', $recipeTitle)->first();
            if (!$recipe) continue;

            foreach ($ings as $ing) {
                $ingredient = DB::table('ingredients')->where('name', $ing['name'])->first();
                if (!$ingredient) continue;

                DB::table('recipe_ingredients')->updateOrInsert(
                    [
                        'recipe_id' => $recipe->id,
                        'ingredient_id' => $ingredient->id
                    ],
                    [
                        'quantity' => $ing['quantity'],
                        'unit' => $ing['unit'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }
    }
}
