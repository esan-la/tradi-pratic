<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    protected $mediaService;

    public function __construct(MediaStorageService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Hotel::withCount('rooms');

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filtre par ville
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        $hotels = $query->paginate(15);
        $cities = Hotel::distinct()->pluck('city');

        return view('admin.hotels.index', compact('hotels', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hotels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240', // 10MB max
            'gallery.*' => 'nullable|image|max:10240',
        ]);

        // Upload image principale
        if ($request->hasFile('image')) {
            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'hotels'
            );
        }

        // Upload galerie
        if ($request->hasFile('gallery')) {
            $validated['gallery'] = json_encode(
                $this->mediaService->uploadMultipleImages(
                    $request->file('gallery'),
                    'hotels/gallery'
                )
            );
        }

        $hotel = Hotel::create($validated);

        activity()
            ->performedOn($hotel)
            ->causedBy(auth()->user())
            ->log('Création d\'un hôtel : ' . $hotel->name);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hôtel créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        $hotel->load(['rooms', 'reservations' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
            'gallery.*' => 'nullable|image|max:10240',
        ]);

        // Upload nouvelle image principale
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne
            if ($hotel->image) {
                $this->mediaService->delete($hotel->image);
            }
            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'hotels'
            );
        }

        // Upload nouvelle galerie
        if ($request->hasFile('gallery')) {
            // Supprimer l'ancienne galerie
            if ($hotel->gallery) {
                $oldGallery = is_array($hotel->gallery) ? $hotel->gallery : json_decode($hotel->gallery, true);
                if ($oldGallery) {
                    $this->mediaService->deleteMultiple($oldGallery);
                }
            }
            $validated['gallery'] = json_encode(
                $this->mediaService->uploadMultipleImages(
                    $request->file('gallery'),
                    'hotels/gallery'
                )
            );
        }

        $hotel->update($validated);

        activity()
            ->performedOn($hotel)
            ->causedBy(auth()->user())
            ->log('Modification d\'un hôtel : ' . $hotel->name);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hôtel mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        // Supprimer les images
        if ($hotel->image) {
            $this->mediaService->delete($hotel->image);
        }

        if ($hotel->gallery) {
            $gallery = is_array($hotel->gallery) ? $hotel->gallery : json_decode($hotel->gallery, true);
            if ($gallery) {
                $this->mediaService->deleteMultiple($gallery);
            }
        }

        $name = $hotel->name;
        $hotel->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Suppression d\'un hôtel : ' . $name);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hôtel supprimé avec succès.');
    }
}
