<?php

namespace App\Http\Controllers;

use App\Models\PubService;
use App\Models\Contact;
use Illuminate\Http\Request;

class PubServiceController extends Controller
{
    /**
     * Display a listing of published services (public)
     */
    public function index(Request $request)
    {
        $query = PubService::with('user')
            ->where('is_published', true)
            ->latest();

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->paginate(12)->withQueryString();
        $featuredServices = PubService::where('is_published', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(4)
            ->get();

        return view('pubservices.index', compact('services', 'featuredServices'));
    }

    /**
     * Display the specified service
     */
    public function show($slug)
    {
        $service = PubService::with('user')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Services similaires
        $relatedServices = PubService::where('is_published', true)
            ->where('id', '!=', $service->id)
            ->where('user_id', $service->user_id)
            ->limit(3)
            ->get();

        return view('pubservices.show', compact('service', 'relatedServices'));
    }

    /**
     * Contact service provider
     */
    public function contact(Request $request, PubService $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string',
        ]);

        // Créer un contact lié au service
        $contact = Contact::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => 'Contact pour le service: ' . $service->title,
            'message' => $validated['message'] . "\n\n---\nService: " . $service->title . " (ID: {$service->id})",
            'status' => 'new',
        ]);

        return back()->with('success', 'Votre message a été envoyé avec succès. Vous serez contacté prochainement.');
    }

    /**
     * Get latest services for homepage
     */
    public function latestForHome($limit = 4)
    {
        return PubService::where('is_published', true)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
