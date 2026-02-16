<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Realisation;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RealisationController extends Controller
{
    protected $mediaService;

    public function __construct(MediaStorageService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Display a listing of realisations
     */
    public function index(Request $request)
    {
        $query = Realisation::query();

        // Recherche
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrage par catégorie
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filtrage par statut de publication
        if ($request->has('published') && $request->published != '') {
            $query->where('is_published', $request->published);
        }

        $realisations = $query->latest()->paginate(15);

        $categories = ['Agriculture', 'Élevage', 'Artisanat', 'Autres'];

        return view('admin.realisations.index', compact('realisations', 'categories'));
    }

    /**
     * Show the form for creating a new realisation
     */
    public function create()
    {
        $categories = ['Agriculture', 'Élevage', 'Artisanat', 'Autres'];
        return view('admin.realisations.create', compact('categories'));
    }

    /**
     * Store a newly created realisation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'image' => 'required|image|max:10240', // 10MB
            'gallery.*' => 'nullable|image|max:10240', // 10MB
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        // Générer le slug
        $validated['slug'] = Str::slug($validated['title']);

        // Assurer l'unicité du slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Realisation::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Upload de l'image principale
        if ($request->hasFile('image')) {
            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'realisations'
            );
        }

        // Upload de la galerie
        if ($request->hasFile('gallery')) {
            $validated['gallery'] = $this->mediaService->uploadMultiple(
                $request->file('gallery'),
                'realisations/gallery'
            );
        }

        // Gérer les checkboxes
        $validated['is_featured'] = $request->has('is_featured') && $request->is_featured == '1';
        $validated['is_published'] = $request->has('is_published') && $request->is_published == '1';

        // Créer la réalisation
        $realisation = Realisation::create($validated);

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($realisation)
                    ->causedBy(auth()->user())
                    ->log('Création de la réalisation : ' . $realisation->title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return redirect()->route('admin.realisations.index')
            ->with('success', 'Réalisation créée avec succès.');
    }

    /**
     * Display the specified realisation
     */
    public function show(Realisation $realisation)
    {
        return view('admin.realisations.show', compact('realisation'));
    }

    /**
     * Show the form for editing the specified realisation
     */
    public function edit(Realisation $realisation)
    {
        $categories = ['Agriculture', 'Élevage', 'Artisanat', 'Autres'];
        return view('admin.realisations.edit', compact('realisation', 'categories'));
    }

    /**
     * Update the specified realisation
     */
    public function update(Request $request, Realisation $realisation)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|image|max:10240', // 10MB, optionnel en édition
            'gallery.*' => 'nullable|image|max:10240', // 10MB
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        // Générer le slug
        $validated['slug'] = Str::slug($validated['title']);

        // Assurer l'unicité du slug (sauf pour la réalisation actuelle)
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Realisation::where('slug', $validated['slug'])
            ->where('id', '!=', $realisation->id)
            ->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Upload de la nouvelle image principale si fournie
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($realisation->image) {
                $this->mediaService->delete($realisation->image);
            }

            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'realisations'
            );
        }

        // Upload de la nouvelle galerie si fournie
        if ($request->hasFile('gallery')) {
            // Supprimer les anciennes images de la galerie
            if ($realisation->gallery && is_array($realisation->gallery)) {
                foreach ($realisation->gallery as $oldImage) {
                    $this->mediaService->delete($oldImage);
                }
            }

            $validated['gallery'] = $this->mediaService->uploadMultiple(
                $request->file('gallery'),
                'realisations/gallery'
            );
        }

        // Gérer les checkboxes
        $validated['is_featured'] = $request->has('is_featured') && $request->is_featured == '1';
        $validated['is_published'] = $request->has('is_published') && $request->is_published == '1';

        // Mettre à jour
        $realisation->update($validated);

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($realisation)
                    ->causedBy(auth()->user())
                    ->log('Modification de la réalisation : ' . $realisation->title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return redirect()->route('admin.realisations.index')
            ->with('success', 'Réalisation mise à jour avec succès.');
    }

    /**
     * Remove the specified realisation
     */
    public function destroy(Realisation $realisation)
    {
        $title = $realisation->title;

        // Supprimer l'image principale
        if ($realisation->image) {
            $this->mediaService->delete($realisation->image);
        }

        // Supprimer les images de la galerie
        if ($realisation->gallery && is_array($realisation->gallery)) {
            foreach ($realisation->gallery as $image) {
                $this->mediaService->delete($image);
            }
        }

        $realisation->delete();

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->log('Suppression de la réalisation : ' . $title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return redirect()->route('admin.realisations.index')
            ->with('success', 'Réalisation supprimée avec succès.');
    }
}
