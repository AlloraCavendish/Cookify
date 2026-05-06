<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    // List all ingredients
    public function index()
    {
        $ingredients = Ingredient::orderBy('id', 'asc')->paginate(10);

        return view('admin.ingredients.index', compact('ingredients'));
    }


    // Show create form
    public function create()
    {
        $categories = Ingredient::select('category')->distinct()->pluck('category');
        $types = ['main', 'optional']; // fixed types
        return view('admin.ingredients.create', compact('categories', 'types'));
    }

    // Store new ingredient
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
            'category_dropdown' => 'nullable|string',
            'category_text' => 'nullable|string|max:100',
            'is_spice' => 'nullable|boolean',
            'type' => 'required|string|in:main,optional',
        ], [
            'category_text.required_without' => 'Please select or enter a category.',
            'category_dropdown.required_without' => 'Please select or enter a category.',
        ]);

        // Determine category: prefer free text if filled
        $category = $request->category_text ?: $request->category_dropdown;

        Ingredient::create([
            'name' => $request->name,
            'category' => $category,
            'is_spice' => $request->is_spice ? 1 : 0,
            'type' => $request->type,
        ]);

        return redirect()->route('admin.ingredients.index')->with('success', 'Ingredient added successfully!');
    }

    // Show edit form
    public function edit(Ingredient $ingredient)
    {
        $categories = Ingredient::select('category')->distinct()->pluck('category');
        $types = ['main', 'optional']; // fixed types
        return view('admin.ingredients.edit', compact('ingredient', 'categories', 'types'));
    }

    // Update ingredient
    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $ingredient->id,
            'category' => 'nullable|string|max:100',
            'is_spice' => 'nullable|boolean',
            'type' => 'nullable|string|max:50',
        ]);

        $ingredient->update([
            'name' => $request->name,
            'category' => $request->category ?: null, // ensure empty strings become null
            'is_spice' => $request->has('is_spice') ? 1 : 0,
            'type' => $request->type ?: null,
        ]);

        return redirect()->route('admin.ingredients.index')->with('success', 'Ingredient updated successfully!');
    }

    // Delete ingredient
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->route('admin.ingredients.index')->with('success', 'Ingredient deleted successfully!');
    }

    // Check duplicate ingredient name (AJAX)
    public function checkName(Request $request)
    {
        $exists = Ingredient::where('name', $request->name)->exists();
        return response()->json(['exists' => $exists]);
    }
}
