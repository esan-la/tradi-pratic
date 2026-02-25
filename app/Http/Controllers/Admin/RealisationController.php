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

        // Filtrage par statut de publication
        if ($request->filled('published')) {
            $query->where('is_published', $request->published);
        }

        // Filtrage par vedette
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured);
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
                $query->orderBy('order', 'asc')->latest();
                break;
        }

        $realisations = $query->paginate(15)->withQueryString();

        // Catégories
        $categories = [
            'Agriculture' => 'Agriculture',
            'Élevage' => 'Élevage',
            'Artisanat' => 'Artisanat',
            'Santé' => 'Santé',
            'Éducation' => 'Éducation',
            'Autres' => 'Autres',
        ];

        // Statistiques
        $stats = [
            'total' => Realisation::count(),
            'published' => Realisation::where('is_published', true)->count(),
            'featured' => Realisation::where('is_featured', true)->count(),
            'draft' => Realisation::where('is_published', false)->count(),
        ];

        return view('admin.realisations.index', compact('realisations', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new realisation
     */
    public function create()
    {
        $categories = [
            'Agriculture' => 'Agriculture',
            'Élevage' => 'Élevage',
            'Artisanat' => 'Artisanat',
            'Autres' => 'Autres',
        ];

        return view('admin.realisations.create', compact('categories'));
    }

    /**
     * Store a newly created realisation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'category' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Générer le slug unique
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);

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
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');
        $validated['order'] = $request->get('order', 0);

        // Créer la réalisation
        $realisation = Realisation::create($validated);

        // Logger l'activité
        $this->logActivity('create', $realisation);

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
        $categories = [
            'Agriculture' => 'Agriculture',
            'Élevage' => 'Élevage',
            'Artisanat' => 'Artisanat',
            'Santé' => 'Santé',
            'Éducation' => 'Éducation',
            'Autres' => 'Autres',
        ];

        return view('admin.realisations.edit', compact('realisation', 'categories'));
    }

    /**
     * Update the specified realisation
     */
    public function update(Request $request, Realisation $realisation)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'order' => 'nullable|integer|min:0',
            'remove_gallery' => 'nullable|array', // IDs des images à supprimer
        ]);

        // Générer le slug unique (sauf pour la réalisation actuelle)
        $validated['slug'] = $this->generateUniqueSlug($validated['title'], $realisation->id);

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

        // Gestion de la galerie
        $currentGallery = $realisation->gallery ?? [];

        // Supprimer les images sélectionnées
        if ($request->filled('remove_gallery')) {
            foreach ($request->remove_gallery as $index) {
                if (isset($currentGallery[$index])) {
                    $this->mediaService->delete($currentGallery[$index]);
                    unset($currentGallery[$index]);
                }
            }
            $currentGallery = array_values($currentGallery); // Réindexer
        }

        // Ajouter les nouvelles images
        if ($request->hasFile('gallery')) {
            $newImages = $this->mediaService->uploadMultiple(
                $request->file('gallery'),
                'realisations/gallery'
            );
            $currentGallery = array_merge($currentGallery, $newImages);
        }

        $validated['gallery'] = !empty($currentGallery) ? $currentGallery : null;

        // Gérer les checkboxes
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');
        $validated['order'] = $request->get('order', 0);

        // Mettre à jour
        $realisation->update($validated);

        // Logger l'activité
        $this->logActivity('update', $realisation);

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
        $this->logActivity('delete', null, "Suppression de la réalisation : $title");

        return redirect()->route('admin.realisations.index')
            ->with('success', 'Réalisation supprimée avec succès.');
    }

    /**
     * Toggle publish status
     */
    public function togglePublish(Realisation $realisation)
    {
        $realisation->update([
            'is_published' => !$realisation->is_published
        ]);

        $status = $realisation->is_published ? 'publiée' : 'dépubliée';

        $this->logActivity('toggle', $realisation, "Réalisation $status");

        return back()->with('success', "Réalisation $status avec succès.");
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Realisation $realisation)
    {
        $realisation->update([
            'is_featured' => !$realisation->is_featured
        ]);

        $status = $realisation->is_featured ? 'mise en vedette' : 'retirée de la vedette';

        $this->logActivity('toggle', $realisation, "Réalisation $status");

        return back()->with('success', "Réalisation $status avec succès.");
    }

    /**
     * Reorder realisations
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:realisations,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            Realisation::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ordre mis à jour avec succès.'
        ]);
    }

    /**
     * Bulk delete realisations
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:realisations,id',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $realisation = Realisation::find($id);
            if ($realisation) {
                // Supprimer les médias
                if ($realisation->image) {
                    $this->mediaService->delete($realisation->image);
                }
                if ($realisation->gallery) {
                    foreach ($realisation->gallery as $image) {
                        $this->mediaService->delete($image);
                    }
                }

                $realisation->delete();
                $count++;
            }
        }

        $this->logActivity('bulk_delete', null, "Suppression en masse de $count réalisation(s)");

        return back()->with('success', "$count réalisation(s) supprimée(s) avec succès.");
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        $query = Realisation::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;

            $query = Realisation::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Log activity
     */
    private function logActivity($action, $realisation = null, $message = null)
    {
        try {
            if (function_exists('activity')) {
                $log = activity();

                if ($realisation) {
                    $log->performedOn($realisation);
                }

                if (auth()->check()) {
                    $log->causedBy(auth()->user());
                }

                $logMessage = $message ?? match($action) {
                    'create' => 'Création de la réalisation : ' . $realisation->title,
                    'update' => 'Modification de la réalisation : ' . $realisation->title,
                    'delete' => 'Suppression de la réalisation',
                    'toggle' => 'Modification du statut de la réalisation : ' . $realisation->title,
                    default => 'Action sur réalisation'
                };

                $log->log($logMessage);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
            \Log::debug('Activity log error: ' . $e->getMessage());
        }
    }
}
