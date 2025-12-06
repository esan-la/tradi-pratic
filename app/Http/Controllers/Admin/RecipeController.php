<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes
     */
    public function index(Request $request)
    {
        $query = Recipe::query();

        // Recherche
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrage par statut de publication
        if ($request->has('published') && $request->published != '') {
            $query->where('is_published', $request->published);
        }

        $recipes = $query->latest()->paginate(15);

        return view('admin.recipes.index', compact('recipes'));
    }

    /**
     * Show the form for creating a new recipe
     */
    public function create()
    {
        return view('admin.recipes.create');
    }

    /**
     * Store a newly created recipe
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|array',
            'ingredients.*' => 'required|string',
            'instructions' => 'required|array',
            'instructions.*' => 'required|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('recipes', 'public');
        }

        // Filtrer les ingrédients et instructions vides
        $data['ingredients'] = array_filter($request->ingredients, fn($item) => !empty($item));
        $data['instructions'] = array_filter($request->instructions, fn($item) => !empty($item));

        Recipe::create($data);

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recette créée avec succès.');
    }

    /**
     * Display the specified recipe
     */
    public function show(Recipe $recipe)
    {
        return view('admin.recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified recipe
     */
    public function edit(Recipe $recipe)
    {
        return view('admin.recipes.edit', compact('recipe'));
    }

    /**
     * Update the specified recipe
     */
    public function update(Request $request, Recipe $recipe)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|array',
            'ingredients.*' => 'required|string',
            'instructions' => 'required|array',
            'instructions.*' => 'required|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($recipe->image) {
                Storage::disk('public')->delete($recipe->image);
            }
            $data['image'] = $request->file('image')->store('recipes', 'public');
        }

        // Filtrer les ingrédients et instructions vides
        $data['ingredients'] = array_filter($request->ingredients, fn($item) => !empty($item));
        $data['instructions'] = array_filter($request->instructions, fn($item) => !empty($item));

        $recipe->update($data);

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recette mise à jour avec succès.');
    }

    /**
     * Remove the specified recipe
     */
    public function destroy(Recipe $recipe)
    {
        // Supprimer l'image associée
        if ($recipe->image) {
            Storage::disk('public')->delete($recipe->image);
        }

        $recipe->delete();

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recette supprimée avec succès.');
    }
}
