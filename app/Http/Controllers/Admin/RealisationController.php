<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Realisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RealisationController extends Controller
{
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Gestion de l'image principale
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('realisations', 'public');
        }

        // Gestion de la galerie
        if ($request->hasFile('gallery')) {
            $galleryImages = [];
            foreach ($request->file('gallery') as $image) {
                $galleryImages[] = $image->store('realisations/gallery', 'public');
            }
            $data['gallery'] = $galleryImages;
        }

        Realisation::create($data);

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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Gestion de l'image principale
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($realisation->image) {
                Storage::disk('public')->delete($realisation->image);
            }
            $data['image'] = $request->file('image')->store('realisations', 'public');
        }

        // Gestion de la galerie
        if ($request->hasFile('gallery')) {
            // Supprimer les anciennes images de la galerie
            if ($realisation->gallery) {
                foreach ($realisation->gallery as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $galleryImages = [];
            foreach ($request->file('gallery') as $image) {
                $galleryImages[] = $image->store('realisations/gallery', 'public');
            }
            $data['gallery'] = $galleryImages;
        }

        $realisation->update($data);

        return redirect()->route('admin.realisations.index')
            ->with('success', 'Réalisation mise à jour avec succès.');
    }

    /**
     * Remove the specified realisation
     */
    public function destroy(Realisation $realisation)
    {
        // Supprimer l'image principale
        if ($realisation->image) {
            Storage::disk('public')->delete($realisation->image);
        }

        // Supprimer les images de la galerie
        if ($realisation->gallery) {
            foreach ($realisation->gallery as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $realisation->delete();

        return redirect()->route('admin.realisations.index')
            ->with('success', 'Réalisation supprimée avec succès.');
    }
}
