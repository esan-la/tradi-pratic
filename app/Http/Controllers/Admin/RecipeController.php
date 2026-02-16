<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    protected $mediaService;

    public function __construct(MediaStorageService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|array',
            'ingredients.*' => 'required|string',
            'instructions' => 'required|array',
            'instructions.*' => 'required|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:10240', // 10MB
            'video_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        // Générer le slug
        $validated['slug'] = Str::slug($validated['title']);

        // Assurer l'unicité du slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Recipe::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Upload de l'image si fournie
        if ($request->hasFile('image')) {
            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'recipes'
            );
        }

        // Filtrer les ingrédients et instructions vides
        $validated['ingredients'] = array_values(array_filter($validated['ingredients'], fn($item) => !empty($item)));
        $validated['instructions'] = array_values(array_filter($validated['instructions'], fn($item) => !empty($item)));

        // Gérer le checkbox is_published
        $validated['is_published'] = $request->has('is_published') && $request->is_published == '1';

        // Créer la recette
        $recipe = Recipe::create($validated);

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($recipe)
                    ->causedBy(auth()->user())
                    ->log('Création de la recette : ' . $recipe->title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|array',
            'ingredients.*' => 'required|string',
            'instructions' => 'required|array',
            'instructions.*' => 'required|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:10240', // 10MB, optionnel en édition
            'video_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        // Générer le slug
        $validated['slug'] = Str::slug($validated['title']);

        // Assurer l'unicité du slug (sauf pour la recette actuelle)
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Recipe::where('slug', $validated['slug'])
            ->where('id', '!=', $recipe->id)
            ->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Upload de la nouvelle image si fournie
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($recipe->image) {
                $this->mediaService->delete($recipe->image);
            }

            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'recipes'
            );
        }

        // Filtrer les ingrédients et instructions vides
        $validated['ingredients'] = array_values(array_filter($validated['ingredients'], fn($item) => !empty($item)));
        $validated['instructions'] = array_values(array_filter($validated['instructions'], fn($item) => !empty($item)));

        // Gérer le checkbox is_published
        $validated['is_published'] = $request->has('is_published') && $request->is_published == '1';

        // Mettre à jour
        $recipe->update($validated);

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($recipe)
                    ->causedBy(auth()->user())
                    ->log('Modification de la recette : ' . $recipe->title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recette mise à jour avec succès.');
    }

    /**
     * Remove the specified recipe
     */
    public function destroy(Recipe $recipe)
    {
        $title = $recipe->title;

        // Supprimer l'image associée
        if ($recipe->image) {
            $this->mediaService->delete($recipe->image);
        }

        $recipe->delete();

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->log('Suppression de la recette : ' . $title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recette supprimée avec succès.');
    }
}
