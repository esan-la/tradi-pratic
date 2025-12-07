<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;


class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Recipe::where('is_published', true);

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $recipes = $query->latest()->paginate(12);

        // Liste des catégories (valeurs exactes de la BD)
        $categories = ['Plats', 'Boissons', 'Desserts', 'Remèdes'];

        return view('recettes.index', compact('recipes', 'categories'));
    }

    public function show($slug)
    {
        $recipe = Recipe::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $relatedRecipes = Recipe::where('id', '!=', $recipe->id)
            ->where('is_published', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('recettes.show', compact('recipe', 'relatedRecipes'));
    }
}
