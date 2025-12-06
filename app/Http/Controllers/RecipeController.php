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

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $recipe = $query->latest()->paginate(9);

        return view('recettes.index', compact('recipe'));
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
// class RecipeController extends Controller
// {
//     public function index(Request $request)
//     {
//         $query = Recipe::published();

//         if ($request->has('category') && $request->category != '') {
//             $query->where('category', $request->category);
//         }

//         if ($request->has('search') && $request->search != '') {
//             $query->where(function($q) use ($request) {
//                 $q->where('title', 'like', '%' . $request->search . '%')
//                   ->orWhere('description', 'like', '%' . $request->search . '%');
//             });
//         }

//         $recipes = $query->latest()->paginate(12);
//         $popularRecipes = Recipe::published()->popular(5)->get();

//         return view('recettes.index', compact('recipes', 'popularRecipes'));
//     }

//     public function show($slug)
//     {
//         $recipe = Recipe::where('slug', $slug)->published()->firstOrFail();
//         $recipe->incrementViews();

//         $relatedRecipes = Recipe::published()
//             ->where('category', $recipe->category)
//             ->where('id', '!=', $recipe->id)
//             ->limit(3)
//             ->get();

//         return view('recettes.show', compact('recipe', 'relatedRecipes'));
//     }
// }
