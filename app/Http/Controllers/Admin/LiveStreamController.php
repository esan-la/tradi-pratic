<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveStream;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;

class LiveStreamController extends Controller
{
    protected $mediaStorage;

    public function __construct(MediaStorageService $mediaStorage)
    {
        $this->mediaStorage = $mediaStorage;
    }

    /**
     * Liste des lives
     */
    public function index(Request $request)
    {
        $query = LiveStream::query()->orderBy('created_at', 'desc');

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Recherche
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $streams = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => LiveStream::count(),
            'live'      => LiveStream::live()->count(),
            'scheduled' => LiveStream::scheduled()->count(),
            'ended'     => LiveStream::ended()->count(),
        ];

        return view('admin.live-streams.index', compact('streams', 'stats'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $categories = LiveStream::CATEGORIES;
        return view('admin.live-streams.create', compact('categories'));
    }

    /**
     * Enregistrer un nouveau live
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string|max:2000',
            'youtube_url'      => 'nullable|string|max:500',
            'scheduled_at'     => 'required|date|after:now',
            'category'         => 'nullable|string|in:' . implode(',', array_keys(LiveStream::CATEGORIES)),
            'chat_enabled'     => 'boolean',
            'is_featured'      => 'boolean',
            'thumbnail'        => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'title.required'       => 'Le titre est obligatoire.',
            'scheduled_at.required'=> 'La date est obligatoire.',
            'scheduled_at.after'   => 'La date doit être dans le futur.',
            'thumbnail.max'        => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        // Extraire YouTube ID
        $validated['youtube_video_id'] = LiveStream::extractYoutubeId($request->youtube_url);
        $validated['chat_enabled'] = $request->boolean('chat_enabled');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'scheduled';

        // Thumbnail
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->mediaStorage->uploadImage(
                $request->file('thumbnail'),
                'live-streams',
                1280,
                720
            );
        }
        // Upload image principale
        // if ($request->hasFile('image')) {
        //     $validated['image'] = $this->mediaService->uploadImage(
        //         $request->file('image'),
        //         'recipes'
        //     );
        // }

        LiveStream::create($validated);

        return redirect()->route('admin.live-streams.index')
            ->with('success', 'Live programmé avec succès !');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(LiveStream $liveStream)
    {
        $categories = LiveStream::CATEGORIES;
        return view('admin.live-streams.edit', compact('liveStream', 'categories'));
    }

    /**
     * Mettre à jour
     */
    public function update(Request $request, LiveStream $liveStream)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string|max:2000',
            'youtube_url'      => 'nullable|string|max:500',
            'scheduled_at'     => 'required|date',
            'category'         => 'nullable|string|in:' . implode(',', array_keys(LiveStream::CATEGORIES)),
            'chat_enabled'     => 'boolean',
            'is_featured'      => 'boolean',
            'thumbnail'        => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $validated['youtube_video_id'] = LiveStream::extractYoutubeId($request->youtube_url);
        $validated['chat_enabled'] = $request->boolean('chat_enabled');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($liveStream->thumbnail) {
                $this->mediaStorage->delete($liveStream->thumbnail);
            }
            $validated['thumbnail'] = $this->mediaStorage->storeImage(
                $request->file('thumbnail'),
                'live-streams',
                1280,
                720
            );
        }

        $liveStream->update($validated);

        return redirect()->route('admin.live-streams.index')
            ->with('success', 'Live mis à jour avec succès !');
    }

    /**
     * Supprimer
     */
    public function destroy(LiveStream $liveStream)
    {
        if ($liveStream->thumbnail) {
            $this->mediaStorage->delete($liveStream->thumbnail);
        }

        $liveStream->delete();

        return redirect()->route('admin.live-streams.index')
            ->with('success', 'Live supprimé avec succès !');
    }

    /**
     * Démarrer le live
     */
    public function goLive(LiveStream $liveStream)
    {
        $liveStream->goLive();

        return redirect()->route('admin.live-streams.index')
            ->with('success', "🔴 \"{$liveStream->title}\" est maintenant EN DIRECT !");
    }

    /**
     * Terminer le live
     */
    public function endStream(LiveStream $liveStream)
    {
        $liveStream->endStream();

        return redirect()->route('admin.live-streams.index')
            ->with('success', "Le live \"{$liveStream->title}\" est terminé.");
    }

    /**
     * Annuler le live
     */
    public function cancel(LiveStream $liveStream)
    {
        $liveStream->cancel();

        return redirect()->route('admin.live-streams.index')
            ->with('warning', "Le live \"{$liveStream->title}\" a été annulé.");
    }
}
