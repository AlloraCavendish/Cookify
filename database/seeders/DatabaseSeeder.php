<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
public function run()
{
$this->call([
IngredientsTableSeeder::class,
RecipesTableSeeder::class,
RecipeIngredientsTableSeeder::class,
]);
}
}
