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
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtrage par difficulté
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Filtrage par statut
        if ($request->filled('published')) {
            $query->where('is_published', $request->published);
        }

        // Tri
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $recipes = $query->paginate(15)->withQueryString();

        // Catégories
        $categories = [
            'Entrée' => 'Entrée',
            'Plat Principal' => 'Plat Principal',
            'Accompagnement' => 'Accompagnement',
            'Dessert' => 'Dessert',
            'Boisson' => 'Boisson',
            'Snack' => 'Snack',
        ];

        // Difficultés
        $difficulties = [
            'Facile' => 'Facile',
            'Moyen' => 'Moyen',
            'Difficile' => 'Difficile',
        ];

        // Statistiques
        $stats = [
            'total' => Recipe::count(),
            'published' => Recipe::where('is_published', true)->count(),
            'featured' => Recipe::where('is_featured', true)->count(),
            'draft' => Recipe::where('is_published', false)->count(),
        ];

        return view('admin.recipes.index', compact('recipes', 'categories', 'difficulties', 'stats'));
    }

    /**
     * Show the form for creating a new recipe
     */
    public function create()
    {
        $categories = [
            'Entrée' => 'Entrée',
            'Plat Principal' => 'Plat Principal',
            'Accompagnement' => 'Accompagnement',
            'Dessert' => 'Dessert',
            'Boisson' => 'Boisson',
            'Snack' => 'Snack',
        ];

        $difficulties = [
            'Facile' => 'Facile',
            'Moyen' => 'Moyen',
            'Difficile' => 'Difficile',
        ];

        return view('admin.recipes.create', compact('categories', 'difficulties'));
    }

    /**
     * Store a newly created recipe
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'category' => 'nullable|string',
            'difficulty' => 'nullable|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'ingredients' => 'required|array|min:1',
            'ingredients.*' => 'required|string',
            'instructions' => 'required|array|min:1',
            'instructions.*' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Générer le slug unique
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);

        // Upload image principale
        if ($request->hasFile('image')) {
            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'recipes'
            );
        }

        // Upload galerie
        if ($request->hasFile('gallery')) {
            $validated['gallery'] = $this->mediaService->uploadMultiple(
                $request->file('gallery'),
                'recipes/gallery'
            );
        }

        // Nettoyer les tableaux (supprimer les entrées vides)
        $validated['ingredients'] = array_values(array_filter($validated['ingredients']));
        $validated['instructions'] = array_values(array_filter($validated['instructions']));

        // Gérer les checkboxes
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Créer la recette
        $recipe = Recipe::create($validated);

        // Logger l'activité
        $this->logActivity('create', $recipe);

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
        $categories = [
            'Entrée' => 'Entrée',
            'Plat Principal' => 'Plat Principal',
            'Accompagnement' => 'Accompagnement',
            'Dessert' => 'Dessert',
            'Boisson' => 'Boisson',
            'Snack' => 'Snack',
        ];

        $difficulties = [
            'Facile' => 'Facile',
            'Moyen' => 'Moyen',
            'Difficile' => 'Difficile',
        ];

        return view('admin.recipes.edit', compact('recipe', 'categories', 'difficulties'));
    }

    /**
     * Update the specified recipe
     */
    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'category' => 'nullable|string',
            'difficulty' => 'nullable|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'ingredients' => 'required|array|min:1',
            'ingredients.*' => 'required|string',
            'instructions' => 'required|array|min:1',
            'instructions.*' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'remove_gallery' => 'nullable|array',
        ]);

        // Générer le slug unique (sauf pour la recette actuelle)
        $validated['slug'] = $this->generateUniqueSlug($validated['title'], $recipe->id);

        // Upload nouvelle image
        if ($request->hasFile('image')) {
            if ($recipe->image) {
                $this->mediaService->delete($recipe->image);
            }
            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'recipes'
            );
        }

        // Gestion de la galerie
        $currentGallery = $recipe->gallery ?? [];

        // Supprimer les images sélectionnées
        if ($request->filled('remove_gallery')) {
            foreach ($request->remove_gallery as $index) {
                if (isset($currentGallery[$index])) {
                    $this->mediaService->delete($currentGallery[$index]);
                    unset($currentGallery[$index]);
                }
            }
            $currentGallery = array_values($currentGallery);
        }

        // Ajouter nouvelles images
        if ($request->hasFile('gallery')) {
            $newImages = $this->mediaService->uploadMultiple(
                $request->file('gallery'),
                'recipes/gallery'
            );
            $currentGallery = array_merge($currentGallery, $newImages);
        }

        $validated['gallery'] = !empty($currentGallery) ? $currentGallery : null;

        // Nettoyer les tableaux
        $validated['ingredients'] = array_values(array_filter($validated['ingredients']));
        $validated['instructions'] = array_values(array_filter($validated['instructions']));

        // Gérer les checkboxes
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Mettre à jour
        $recipe->update($validated);

        // Logger l'activité
        $this->logActivity('update', $recipe);

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recette mise à jour avec succès.');
    }

    /**
     * Remove the specified recipe
     */
    public function destroy(Recipe $recipe)
    {
        $title = $recipe->title;

        // Supprimer l'image principale
        if ($recipe->image) {
            $this->mediaService->delete($recipe->image);
        }

        // Supprimer les images de la galerie
        if ($recipe->gallery && is_array($recipe->gallery)) {
            foreach ($recipe->gallery as $image) {
                $this->mediaService->delete($image);
            }
        }

        $recipe->delete();

        // Logger l'activité
        $this->logActivity('delete', null, "Suppression de la recette : $title");

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recette supprimée avec succès.');
    }

    /**
     * Toggle publish status
     */
    public function togglePublish(Recipe $recipe)
    {
        $recipe->update([
            'is_published' => !$recipe->is_published
        ]);

        $status = $recipe->is_published ? 'publiée' : 'dépubliée';
        $this->logActivity('toggle', $recipe, "Recette $status");

        return back()->with('success', "Recette $status avec succès.");
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Recipe $recipe)
    {
        $recipe->update([
            'is_featured' => !$recipe->is_featured
        ]);

        $status = $recipe->is_featured ? 'mise en vedette' : 'retirée de la vedette';
        $this->logActivity('toggle', $recipe, "Recette $status");

        return back()->with('success', "Recette $status avec succès.");
    }

    /**
     * Bulk delete recipes
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:recipes,id',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $recipe = Recipe::find($id);
            if ($recipe) {
                // Supprimer les médias
                if ($recipe->image) {
                    $this->mediaService->delete($recipe->image);
                }
                if ($recipe->gallery) {
                    foreach ($recipe->gallery as $image) {
                        $this->mediaService->delete($image);
                    }
                }

                $recipe->delete();
                $count++;
            }
        }

        $this->logActivity('bulk_delete', null, "Suppression en masse de $count recette(s)");

        return back()->with('success', "$count recette(s) supprimée(s) avec succès.");
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        $query = Recipe::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;

            $query = Recipe::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Log activity
     */
    private function logActivity($action, $recipe = null, $message = null)
    {
        try {
            if (function_exists('activity')) {
                $log = activity();

                if ($recipe) {
                    $log->performedOn($recipe);
                }

                if (auth()->check()) {
                    $log->causedBy(auth()->user());
                }

                $logMessage = $message ?? match($action) {
                    'create' => 'Création de la recette : ' . $recipe->title,
                    'update' => 'Modification de la recette : ' . $recipe->title,
                    'delete' => 'Suppression de la recette',
                    'toggle' => 'Modification du statut de la recette : ' . $recipe->title,
                    default => 'Action sur recette'
                };

                $log->log($logMessage);
            }
        } catch (\Exception $e) {
            \Log::debug('Activity log error: ' . $e->getMessage());
        }
    }
}
