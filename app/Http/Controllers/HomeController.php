<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Recipe;
use App\Models\Realisation;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::approved()
            ->featured()
            ->latest()
            ->limit(6)
            ->get();

        $featuredRealisations = Realisation::published()
            ->featured()
            ->orderBy('order')
            ->limit(6)
            ->get();

        $latestRecipes = Recipe::published()
            ->latest()
            ->limit(3)
            ->get();

        return view('home', compact('testimonials', 'featuredRealisations', 'latestRecipes'));
    }
}
