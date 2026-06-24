<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PubService;
use App\Models\User;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PubServiceController extends Controller
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
        $query = PubService::with('user');

        // Filtre par statut
        if ($request->has('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_published', false);
            }
        }

        // Filtre par utilisateur
        if ($request->has('user') && $request->user) {
            $query->where('user_id', $request->user);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $services = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pub-services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.pub-services.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pub_services,slug',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'required|image|max:10240', // 10MB
            'is_published' => 'boolean',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Assurer l'unicité du slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (PubService::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Upload de l'image
        $validated['image'] = $this->mediaService->uploadImage(
            $request->file('image'),
            'pub-services'
        );

        // Gérer le checkbox is_published
        $validated['is_published'] = $request->has('is_published') && $request->is_published == '1';

        // Créer le service
        $service = PubService::create($validated);

        // Logger l'activité si le package est installé
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($service)
                    ->causedBy(auth()->user())
                    ->log('Création de la publicité de service : ' . $service->title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return redirect()->route('admin.pub-services.index')
            ->with('success', 'Publicité créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PubService $pubService)
    {
        $pubService->load('user');
        return view('admin.pub-services.show', ['service' => $pubService]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PubService $pubService)
    {
        $pubService->load('user');
        $users = User::all();
        return view('admin.pub-services.edit', [
            'service' => $pubService,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PubService $pubService)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pub_services,slug,' . $pubService->id,
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:10240', // 10MB, optionnel en édition
            'is_published' => 'boolean',
        ]);

        // Générer le slug si vide
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Assurer l'unicité du slug (sauf pour le service actuel)
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (PubService::where('slug', $validated['slug'])
            ->where('id', '!=', $pubService->id)
            ->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Upload nouvelle image si fournie
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($pubService->image) {
                $this->mediaService->delete($pubService->image);
            }

            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'pub-services'
            );
        }

        // Gérer le checkbox is_published
        $validated['is_published'] = $request->has('is_published') && $request->is_published == '1';

        // Mettre à jour
        $pubService->update($validated);

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($pubService)
                    ->causedBy(auth()->user())
                    ->log('Modification de la publicité : ' . $pubService->title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return redirect()->route('admin.pub-services.index')
            ->with('success', 'Publicité mise à jour avec succès.');
    }

    /**
     * Toggle publish status
     */
    public function togglePublish(PubService $pubService)
    {
        $pubService->update([
            'is_published' => !$pubService->is_published
        ]);

        $status = $pubService->is_published ? 'publié' : 'dépublié';

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($pubService)
                    ->causedBy(auth()->user())
                    ->log("Service {$status} : " . $pubService->title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return back()->with('success', "Service {$status} avec succès.");
    }

    /**
     * Approve a service (for moderation workflow)
     */
    public function approve(PubService $pubService)
    {
        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($pubService)
                    ->causedBy(auth()->user())
                    ->log('Approbation du service : ' . $pubService->title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return back()->with('success', 'Service approuvé avec succès.');
    }

    /**
     * Reject a service (for moderation workflow)
     */
    public function reject(PubService $pubService)
    {
        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($pubService)
                    ->causedBy(auth()->user())
                    ->log('Rejet du service : ' . $pubService->title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return back()->with('warning', 'Service rejeté.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PubService $pubService)
    {
        $title = $pubService->title;

        // Supprimer l'image
        if ($pubService->image) {
            $this->mediaService->delete($pubService->image);
        }

        $pubService->delete();

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->log('Suppression de la publicité : ' . $title);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return redirect()->route('admin.pub-services.index')
            ->with('success', 'Publicité supprimée avec succès.');
    }
}
