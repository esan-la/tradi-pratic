<?php

namespace App\Http\Controllers;

use App\Models\Realisation;
use Illuminate\Http\Request;

class RealisationController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = Realisation::published();

    //     if ($request->has('category') && $request->category != 'all') {
    //         $query->byCategory($request->category);
    //     }

    //     $realisations = $query->orderBy('order')->orderBy('created_at', 'desc')->paginate(12);

    //     $categories = [
    //         'all' => 'Toutes',
    //         'agriculture' => 'Agriculture',
    //         'elevage' => 'Élevage',
    //         'artisanat' => 'Artisanat',
    //     ];

    //     return view('realisations.index', compact('realisations', 'categories'));
    // }


    // Dans RealisationController::index()
    public function index(Request $request)
    {
        $query = Realisation::where('is_published', true);

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri
        $realisations = $query->orderBy('order')
                            ->orderBy('created_at', 'desc')
                            ->paginate(12);

        // Liste des catégories (valeurs exactes de la BD)
        $categories = ['Agriculture', 'Élevage', 'Artisanat', 'Autres'];

        return view('realisations.index', compact('realisations', 'categories'));
    }

    public function show($slug)
    {
        $realisation = Realisation::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Réalisations similaires (même catégorie)
        $relatedRealisations = Realisation::where('is_published', true)
            ->where('category', $realisation->category)
            ->where('id', '!=', $realisation->id)
            ->latest()
            ->take(3)
            ->get();

        // Navigation précédent/suivant
        $previousRealisation = Realisation::where('is_published', true)
            ->where('created_at', '<', $realisation->created_at)
            ->orderBy('created_at', 'desc')
            ->first();

        $nextRealisation = Realisation::where('is_published', true)
            ->where('created_at', '>', $realisation->created_at)
            ->orderBy('created_at', 'asc')
            ->first();

        return view('realisations.show', compact(
            'realisation',
            'relatedRealisations',
            'previousRealisation',
            'nextRealisation'
        ));
    }
}
