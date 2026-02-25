<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RecipeController extends Controller
{
    /**
     * Display a listing of published recipes
     */
    public function index(Request $request)
    {
        $query = Recipe::where('is_published', true);

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtrage par difficulté
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Recherche
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
            case 'time':
                $query->orderByRaw('(COALESCE(prep_time, 0) + COALESCE(cook_time, 0)) ASC');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $recipes = $query->paginate($perPage)->withQueryString();

        // Catégories disponibles
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

        // Statistiques (cache 1h)
        $stats = Cache::remember('recipes_public_stats', 3600, function () {
            return [
                'total' => Recipe::where('is_published', true)->count(),
                'by_category' => Recipe::where('is_published', true)
                    ->selectRaw('category, COUNT(*) as count')
                    ->groupBy('category')
                    ->pluck('count', 'category')
                    ->toArray(),
                'by_difficulty' => Recipe::where('is_published', true)
                    ->selectRaw('difficulty, COUNT(*) as count')
                    ->groupBy('difficulty')
                    ->pluck('count', 'difficulty')
                    ->toArray(),
            ];
        });

        return view('recipes.index', compact(
            'recipes',
            'categories',
            'difficulties',
            'stats'
        ));
    }

    /**
     * Display the specified recipe
     */
    public function show($slug)
    {
        // Récupérer la recette
        $recipe = Recipe::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Incrémenter les vues (une fois par session)
        $this->incrementViews($recipe);

        // Recettes similaires (même catégorie)
        $relatedRecipes = Recipe::where('is_published', true)
            ->where('category', $recipe->category)
            ->where('id', '!=', $recipe->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Navigation précédent/suivant
        $previousRecipe = Recipe::where('is_published', true)
            ->where('id', '<', $recipe->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextRecipe = Recipe::where('is_published', true)
            ->where('id', '>', $recipe->id)
            ->orderBy('id', 'asc')
            ->first();

        // Meta tags pour SEO
        $metaTitle = $recipe->title . ' - Nos Recettes';
        $metaDescription = $recipe->short_description ??
                          \Str::limit(strip_tags($recipe->description), 160);

        return view('recipes.show', compact(
            'recipe',
            'relatedRecipes',
            'previousRecipe',
            'nextRecipe',
            'metaTitle',
            'metaDescription'
        ));
    }

    /**
     * Filter by category
     */
    public function category($category)
    {
        $recipes = Recipe::where('is_published', true)
            ->where('category', $category)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

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

        $categoryName = $category;

        // Statistiques
        $stats = [
            'total' => Recipe::where('is_published', true)
                ->where('category', $category)
                ->count(),
        ];

        return view('recipes.category', compact(
            'recipes',
            'categories',
            'difficulties',
            'category',
            'categoryName',
            'stats'
        ));
    }

    /**
     * Search recipes (AJAX)
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

        $recipes = Recipe::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%");
            })
            ->select('id', 'slug', 'title', 'category', 'difficulty', 'image', 'short_description', 'prep_time', 'cook_time')
            ->limit(10)
            ->get()
            ->map(function($recipe) {
                return [
                    'id' => $recipe->id,
                    'slug' => $recipe->slug,
                    'title' => $recipe->title,
                    'category' => $recipe->category,
                    'difficulty' => $recipe->difficulty,
                    'image' => $recipe->image ? asset('storage/' . $recipe->image) : null,
                    'excerpt' => $recipe->short_description ?? \Str::limit(strip_tags($recipe->description), 100),
                    'total_time' => ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0),
                    'url' => route('recipes.show', $recipe->slug),
                ];
            });

        return response()->json([
            'results' => $recipes,
            'count' => $recipes->count()
        ]);
    }

    /**
     * Print recipe (version imprimable)
     */
    public function print($slug)
    {
        $recipe = Recipe::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('recipes.print', compact('recipe'));
    }

    /**
     * Increment views count (once per session)
     */
    private function incrementViews($recipe)
    {
        $sessionKey = 'recipe_' . $recipe->id . '_viewed';

        if (!session()->has($sessionKey)) {
            $recipe->increment('views');
            session()->put($sessionKey, true);
        }
    }
}
