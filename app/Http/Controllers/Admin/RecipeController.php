<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    // List all recipes with ingredients
    public function index()
    {
        $recipes = Recipe::with('ingredients')->latest()->paginate(10);
        return view('admin.recipes.index', compact('recipes'));
    }

    // Show create form
    public function create()
    {
        // fetch available ingredients
        $ingredients = Ingredient::orderBy('name')->get();

        // units list — add/remove to taste or move to config/DB later
        $units = [
            'g', 'kg', 'ml', 'l',
            'tbsp', 'tsp', 'cup', 'pcs',
            'slice', 'pinch'
        ];

        return view('admin.recipes.create', compact('ingredients', 'units'));
    }

    // Store new recipe
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'nullable|string',
            'cooking_time' => 'nullable|integer',
            'difficulty' => 'nullable|string',
            'cuisine' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => ['nullable|string|required|regex:regex:/^(\d+(\.\d+)?|\d+\s\d+\/\d+|\d+\/\d+)$/'],
            'ingredients.*.unit' => 'nullable|string',
        ]);

        // If validation fails, redirect back with old input and ingredients
        if ($validator->fails()) {
            $ingredients = Ingredient::all();
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput()
                             ->with(compact('ingredients'));
        }

        // Create recipe
        $data = $request->only([
            'title', 'description', 'steps', 'cooking_time',
            'difficulty', 'cuisine'
        ]);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('recipes', 'public');
        }

        // Create recipe
        $recipe = Recipe::create([
            'title' => $request->title,
            'description' => $request->description,
            'steps' => $request->steps,
            'cooking_time' => $request->cooking_time,
            'difficulty' => $request->difficulty,
            'cuisine' => $request->cuisine,
            'user_id' => auth()->id(),
        ]);

        // Attach ingredients to pivot table
        if ($request->has('ingredients')) {
            $attachData = [];
            foreach ($request->ingredients as $ing) {
                if (!empty($ing['id'])) {
                    $attachData[$ing['id']] = [
                        'quantity' => $ing['quantity'] ?? null,
                        'unit' => $ing['unit'] ?? null,
                    ];
                }
            }
            $recipe->ingredients()->attach($attachData);
        }

        return redirect()->route('admin.recipes.index')->with('success', 'Recipe created successfully.');
    }

    // Show edit form
    public function edit(Recipe $recipe)
    {
        $ingredients = Ingredient::all();
        $recipe->load('ingredients'); // eager load for pre-filling quantities

        // units list — same as in create()
        $units = [
            'g', 'kg', 'ml', 'l',
            'tbsp', 'tsp', 'cup', 'pcs',
            'slice', 'pinch'
        ];

        return view('admin.recipes.edit', compact('recipe', 'ingredients', 'units'));
    }


    // Update recipe
    public function update(Request $request, Recipe $recipe)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'nullable|string',
            'cooking_time' => 'nullable|integer',
            'difficulty' => 'nullable|string',
            'cuisine' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            $ingredients = Ingredient::all();
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput()
                             ->with(compact('ingredients'));
        }

        $data = $request->only([
            'title', 'description', 'steps', 'cooking_time',
            'difficulty', 'cuisine'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('recipes', 'public');
        }

        $recipe->update($data);

        // Sync ingredients
        $attachData = [];
        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $ing) {
                if (!empty($ing['id'])) {
                    $attachData[$ing['id']] = [
                        'quantity' => $ing['quantity'] ?? null,
                        'unit' => $ing['unit'] ?? null,
                        'type' => $ing['type'] ?? 'main', // 👈 NEW
                    ];
                }
            }
        }
        $recipe->ingredients()->sync($attachData);

        return redirect()->route('admin.recipes.index')->with('success', 'Recipe updated successfully.');
    }

    // Delete recipe
    public function destroy(Recipe $recipe)
    {
        $recipe->ingredients()->detach(); // remove pivot records first
        $recipe->delete();
        return redirect()->route('admin.recipes.index')->with('success', 'Recipe deleted successfully.');
    }

     public function searchIngredients(Request $request)
    {
        $query = $request->get('q'); // text from search bar

        $ingredients = \App\Models\Ingredient::where('name', 'like', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'name']); // keep it light

        return response()->json($ingredients);
    }   
}
