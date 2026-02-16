<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Testimonial::query();

        // Filtre par statut
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filtre par note
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $testimonials = $query->latest()->paginate(15);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    /**
     * Approve a testimonial
     */
    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['is_approved' => true]);

        activity()
            ->performedOn($testimonial)
            ->causedBy(auth()->user())
            ->log('Approbation d\'un témoignage de ' . $testimonial->name);

        return back()->with('success', 'Témoignage approuvé avec succès.');
    }

    /**
     * Reject a testimonial
     */
    public function reject(Testimonial $testimonial)
    {
        $testimonial->update(['is_approved' => false]);

        activity()
            ->performedOn($testimonial)
            ->causedBy(auth()->user())
            ->log('Rejet d\'un témoignage de ' . $testimonial->name);

        return back()->with('warning', 'Témoignage rejeté.');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Testimonial $testimonial)
    {
        $testimonial->update(['is_featured' => !$testimonial->is_featured]);

        return back()->with('success', 'Statut mis en avant mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        // Supprimer l'avatar si existe
        if ($testimonial->avatar) {
            $this->mediaService->delete($testimonial->avatar);
        }

        $name = $testimonial->name;
        $testimonial->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Suppression d\'un témoignage de ' . $name);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Témoignage supprimé avec succès.');
    }

    /**
     * Get statistics
     */
    public function stats()
    {
        $stats = [
            'total' => Testimonial::count(),
            'approved' => Testimonial::where('is_approved', true)->count(),
            'pending' => Testimonial::where('is_approved', false)->count(),
            'featured' => Testimonial::where('is_featured', true)->count(),
            'average_rating' => round(Testimonial::avg('rating'), 1),
        ];

        return response()->json($stats);
    }
}
