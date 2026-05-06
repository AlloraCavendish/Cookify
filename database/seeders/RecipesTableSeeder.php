<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecipesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();


$recipes = [
[
'title' => 'Tomato Omelette',
'description' => 'Quick omelette with tomato and onion.',
'steps' => "1. Beat eggs\n2. Chop tomato and onion\n3. Fry onion, add tomato\n4. Add eggs and cook",
'cooking_time' => 10,
'difficulty' => 'easy',
'cuisine' => 'international',
],
[
'title' => 'Simple Fried Rice',
'description' => 'Leftover rice turned into tasty fried rice.',
'steps' => "1. Heat oil\n2. Saute garlic and onion\n3. Add rice and soy sauce\n4. Add egg and mix",
'cooking_time' => 15,
'difficulty' => 'easy',
'cuisine' => 'asian',
],
[
'title' => 'Garlic Butter Chicken',
'description' => 'Pan-seared chicken with garlic butter.',
'steps' => "1. Season chicken\n2. Sear chicken in butter\n3. Add garlic and baste\n4. Finish in oven if needed",
'cooking_time' => 30,
'difficulty' => 'medium',
'cuisine' => 'international',
],
];


foreach ($recipes as $r) {
DB::table('recipes')->updateOrInsert(
['title' => $r['title']],
[
'description' => $r['description'],
'steps' => $r['steps'],
'cooking_time' => $r['cooking_time'],
'difficulty' => $r['difficulty'],
'cuisine' => $r['cuisine'],
'created_at' => $now,
'updated_at' => $now,
]
);
}
    }
}
