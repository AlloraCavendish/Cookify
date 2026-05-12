<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'category',
        'is_spice',
        'type'
    ];

    public function recipes()
    {
        return $this->belongsToMany(
            Recipe::class,
            'recipe_ingredients',
            'ingredient_id',
            'recipe_id'
        )->withPivot('quantity', 'unit');
    }
    
    // Automatically lowercase name before saving
    protected static function booted()
    {
        static::saving(function ($ingredient) {
            $ingredient->name = strtolower(trim($ingredient->name));
        });
    }
}
