<?php

namespace App\Http\Controllers;

use App\Models\Realisation;
use Illuminate\Http\Request;

class RealisationController extends Controller
{
    public function index(Request $request)
    {
        $query = Realisation::published();

        if ($request->has('category') && $request->category != 'all') {
            $query->byCategory($request->category);
        }

        $realisations = $query->orderBy('order')->orderBy('created_at', 'desc')->paginate(12);

        $categories = [
            'all' => 'Toutes',
            'agriculture' => 'Agriculture',
            'elevage' => 'Élevage',
            'artisanat' => 'Artisanat',
        ];

        return view('realisations.index', compact('realisations', 'categories'));
    }

    public function show($slug)
    {
        $realisation = Realisation::where('slug', $slug)->published()->firstOrFail();

        $relatedRealisations = Realisation::published()
            ->where('category', $realisation->category)
            ->where('id', '!=', $realisation->id)
            ->limit(3)
            ->get();

        return view('realisations.show', compact('realisation', 'relatedRealisations'));
    }
}


// public function index(Request $request)
//     {
//         $query = Realisation::published();

//         // Filtrage par catégorie
//         if ($request->has('category') && $request->category != '') {
//             $query->where('category', $request->category);
//         }

//         // Recherche
//         if ($request->has('search') && $request->search != '') {
//             $search = $request->search;
//             $query->where(function($q) use ($search) {
//                 $q->where('title', 'like', "%{$search}%")
//                   ->orWhere('description', 'like', "%{$search}%");
//             });
//         }

//         $realisations = $query->latest()->paginate(12);

//         $categories = ['Agriculture', 'Élevage', 'Artisanat', 'Autres'];

//         return view('realisations.index', compact('realisations', 'categories'));
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show(Realisation $realisation)
//     {
//         // Vérifier si la réalisation est publiée (sauf pour les admins)
//         if (!$realisation->is_published && !auth()->check()) {
//             abort(404);
//         }

//         // Réalisations similaires (même catégorie)
//         $relatedRealisations = Realisation::published()
//             ->where('category', $realisation->category)
//             ->where('id', '!=', $realisation->id)
//             ->latest()
//             ->take(3)
//             ->get();

//         // Navigation précédent/suivant - CORRECTION ICI
//         $previousRealisation = Realisation::published()
//             ->where('id', '<', $realisation->id) // ✅ Cette syntaxe est correcte
//             ->orderBy('id', 'desc')
//             ->first();

//         $nextRealisation = Realisation::published()
//             ->where('id', '>', $realisation->id) // ✅ Cette syntaxe est correcte
//             ->orderBy('id', 'asc')
//             ->first();

//         return view('realisations.show', compact(
//             'realisation',
//             'relatedRealisations',
//             'previousRealisation',
//             'nextRealisation'
//         ));
//     }
