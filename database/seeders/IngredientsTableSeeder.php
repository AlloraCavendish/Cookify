<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IngredientsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $ingredients = [
            ['name' => 'egg', 'category' => 'protein', 'is_spice' => false],
            ['name' => 'onion', 'category' => 'vegetable', 'is_spice' => false],
            ['name' => 'tomato', 'category' => 'vegetable', 'is_spice' => false],
            ['name' => 'salt', 'category' => 'pantry', 'is_spice' => true],
            ['name' => 'pepper', 'category' => 'pantry', 'is_spice' => true],
            ['name' => 'garlic', 'category' => 'vegetable', 'is_spice' => true],
            ['name' => 'rice', 'category' => 'carb', 'is_spice' => false],
            ['name' => 'soy sauce', 'category' => 'condiment', 'is_spice' => false],
            ['name' => 'butter', 'category' => 'dairy', 'is_spice' => false],
            ['name' => 'milk', 'category' => 'dairy', 'is_spice' => false],
            ['name' => 'flour', 'category' => 'pantry', 'is_spice' => false],
            ['name' => 'chicken', 'category' => 'protein', 'is_spice' => false],
            ['name' => 'carrot', 'category' => 'vegetable', 'is_spice' => false],
            ['name' => 'bell pepper', 'category' => 'vegetable', 'is_spice' => false],
            ['name' => 'cumin', 'category' => 'spice', 'is_spice' => true],
        ];

        foreach ($ingredients as $ing) {
            DB::table('ingredients')->updateOrInsert(
                ['name' => $ing['name']],
                [
                    'category' => $ing['category'],
                    'is_spice' => $ing['is_spice'],
                    'type' => $ing['is_spice'] ? 'optional' : 'main', // ✅ classify type
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
