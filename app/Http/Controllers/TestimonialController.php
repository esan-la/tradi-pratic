<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    protected $mediaService;

    public function __construct(MediaStorageService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Store a public testimonial
     */
    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
            'avatar' => 'nullable|image|max:2048', // 2MB max
        ]);

        // Upload avatar si fourni
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $this->mediaService->uploadImage(
                $request->file('avatar'),
                'testimonials/avatars'
            );
        }

        // Créer le témoignage (non approuvé par défaut)
        $testimonial = Testimonial::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'location' => $validated['location'] ?? null,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'avatar' => $validated['avatar'] ?? null,
            'is_approved' => false, // Modération requise
        ]);

        return back()->with('success', 'Merci pour votre témoignage ! Il sera publié après validation.');
    }
}
