<?php

namespace App\Http\Controllers;

use App\Models\Realisation;
use App\Models\Recipe;
use App\Models\Testimonial;
use App\Models\PubService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Réalisations en vedette
        $featuredRealisations = Realisation::where('is_published', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(3)
            ->get();

        // Recettes récentes
        $latestRecipes = Recipe::where('is_published', true)
            ->latest()
            ->limit(3)
            ->get();

        // Témoignages approuvés
        $testimonials = Testimonial::where('is_approved', true)
            ->latest()
            ->limit(3)
            ->get();

        // NOUVEAU: Publicités de services (remplace la section services)
        $latestServices = PubService::where('is_published', true)
            ->latest()
            ->limit(8) // 8 services pour afficher 4 à la fois avec navigation
            ->get();

        return view('home', compact(
            'featuredRealisations',
            'latestRecipes',
            'testimonials',
            'latestServices'
        ));
    }
}
