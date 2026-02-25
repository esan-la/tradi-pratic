<?php

namespace App\Http\Controllers;

use App\Models\Realisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RealisationController extends Controller
{
    /**
     * Display a listing of published realisations
     */
    public function index(Request $request)
    {
        $query = Realisation::where('is_published', true);

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Recherche par titre ou description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
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
                $query->orderBy('order', 'asc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $realisations = $query->paginate($perPage)->withQueryString();

        // Catégories disponibles
        $categories = [
            'Agriculture' => 'Agriculture',
            'Élevage' => 'Élevage',
            'Artisanat' => 'Artisanat',
            'Santé' => 'Santé',
            'Éducation' => 'Éducation',
            'Autres' => 'Autres',
        ];

        // Statistiques (cache 1h)
        $stats = Cache::remember('realisations_public_stats', 3600, function () {
            return [
                'total' => Realisation::where('is_published', true)->count(),
                'by_category' => Realisation::where('is_published', true)
                    ->selectRaw('category, COUNT(*) as count')
                    ->groupBy('category')
                    ->pluck('count', 'category')
                    ->toArray(),
            ];
        });

        return view('realisations.index', compact(
            'realisations',
            'categories',
            'stats'
        ));
    }

    /**
     * Display the specified realisation
     */
    public function show($slug)
    {
        // Récupérer la réalisation
        $realisation = Realisation::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Incrémenter les vues (une fois par session)
        $this->incrementViews($realisation);

        // Réalisations similaires (même catégorie)
        $relatedRealisations = Realisation::where('is_published', true)
            ->where('category', $realisation->category)
            ->where('id', '!=', $realisation->id)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Navigation précédent/suivant
        $previousRealisation = Realisation::where('is_published', true)
            ->where('id', '<', $realisation->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextRealisation = Realisation::where('is_published', true)
            ->where('id', '>', $realisation->id)
            ->orderBy('id', 'asc')
            ->first();

        // Meta tags pour SEO
        $metaTitle = $realisation->title . ' - Nos Réalisations';
        $metaDescription = $realisation->short_description ??
                          \Str::limit(strip_tags($realisation->description), 160);

        return view('realisations.show', compact(
            'realisation',
            'relatedRealisations',
            'previousRealisation',
            'nextRealisation',
            'metaTitle',
            'metaDescription'
        ));
    }

    /**
     * Filter by category
     */
    public function category($category)
    {
        $realisations = Realisation::where('is_published', true)
            ->where('category', $category)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = [
            'Agriculture' => 'Agriculture',
            'Élevage' => 'Élevage',
            'Artisanat' => 'Artisanat',
            'Autres' => 'Autres',
        ];

        $categoryName = $category;

        // Statistiques
        $stats = [
            'total' => Realisation::where('is_published', true)->where('category', $category)->count(),
        ];

        return view('realisations.category', compact(
            'realisations',
            'categories',
            'category',
            'categoryName',
            'stats'
        ));
    }

    /**
     * Search realisations (AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'message' => 'Veuillez saisir au moins 2 caractères'
            ]);
        }

        $realisations = Realisation::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%");
            })
            ->select('id', 'slug', 'title', 'category', 'image', 'short_description')
            ->limit(10)
            ->get()
            ->map(function($realisation) {
                return [
                    'id' => $realisation->id,
                    'slug' => $realisation->slug,
                    'title' => $realisation->title,
                    'category' => $realisation->category,
                    'image' => $realisation->image ? asset('storage/' . $realisation->image) : null,
                    'excerpt' => $realisation->short_description ?? \Str::limit(strip_tags($realisation->description), 100),
                    'url' => route('realisations.show', $realisation->slug),
                ];
            });

        return response()->json([
            'results' => $realisations,
            'count' => $realisations->count()
        ]);
    }

    /**
     * Increment views count (once per session)
     */
    private function incrementViews($realisation)
    {
        $sessionKey = 'realisation_' . $realisation->id . '_viewed';

        if (!session()->has($sessionKey)) {
            $realisation->increment('views');
            session()->put($sessionKey, true);
        }
    }
}
