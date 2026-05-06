<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'ingredients',
        'steps',
        'cooking_time',
        'difficulty',
        'cuisine',
        'image',
        'user_id',
    ];

    // Relationship with ingredients
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }
}
