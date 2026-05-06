<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    protected $table = 'recipe_ingredients'; // pivot table name

    protected $fillable = [
        'recipe_id',
        'ingredient_id',
        'quantity',
        'unit',
        'type', // main | optional
    ];

    /**
     * Relationship to Recipe
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Relationship to Ingredient
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
