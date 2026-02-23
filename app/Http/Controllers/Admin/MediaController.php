<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaImage;
use App\Models\MediaVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display media gallery
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');

        // Utiliser paginate() au lieu de get()
        if ($type === 'videos') {
            $images = collect(); // Collection vide
            $videos = MediaVideo::with('user')->latest()->paginate(20);
        } elseif ($type === 'images') {
            $images = MediaImage::with('user')->latest()->paginate(20);
            $videos = collect(); // Collection vide
        } else {
            // Type = 'all'
            $images = MediaImage::with('user')->latest()->paginate(20, ['*'], 'images_page');
            $videos = MediaVideo::with('user')->latest()->paginate(20, ['*'], 'videos_page');
        }

        return view('admin.media.index', compact('images', 'videos', 'type'));
    }

    /**
     * Show upload form
     */
    public function create()
    {
        return view('admin.media.create');
    }

    /**
     * Store images (multiple upload)
     */
    public function storeImages(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        $uploaded = 0;

        foreach ($request->file('images') as $image) {
            // Générer un nom unique
            $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();

            // Stocker l'image
            $path = $image->storeAs('media/images', $filename, 'public');

            // Créer l'entrée en BDD
            MediaImage::create([
                'user_id' => auth()->id(),
                'image_path' => $path,
                'is_published' => true,
            ]);

            $uploaded++;
        }

        return back()->with('success', "$uploaded image(s) uploadée(s) avec succès.");
    }

    /**
     * Store video (YouTube/Vimeo URL)
     */
    public function storeVideo(Request $request)
    {
        $request->validate([
            'video_url' => 'required|url',
        ]);

        MediaVideo::create([
            'user_id' => auth()->id(),
            'video_url' => $request->video_url,
            'is_published' => true,
        ]);

        return back()->with('success', 'Vidéo ajoutée avec succès.');
    }

    /**
     * Toggle publish status - Image
     */
    public function toggleImage($id)
    {
        $image = MediaImage::findOrFail($id);
        $image->update(['is_published' => !$image->is_published]);

        $status = $image->is_published ? 'publiée' : 'dépubliée';
        return back()->with('success', "Image $status avec succès.");
    }

    /**
     * Toggle publish status - Video
     */
    public function toggleVideo($id)
    {
        $video = MediaVideo::findOrFail($id);
        $video->update(['is_published' => !$video->is_published]);

        $status = $video->is_published ? 'publiée' : 'dépubliée';
        return back()->with('success', "Vidéo $status avec succès.");
    }

    /**
     * Delete image
     */
    public function destroyImage($id)
    {
        $image = MediaImage::findOrFail($id);
        $image->delete(); // Le modèle supprime automatiquement le fichier

        return back()->with('success', 'Image supprimée avec succès.');
    }

    /**
     * Delete video
     */
    public function destroyVideo($id)
    {
        $video = MediaVideo::findOrFail($id);
        $video->delete();

        return back()->with('success', 'Vidéo supprimée avec succès.');
    }

    /**
     * Bulk delete images
     */
    public function bulkDeleteImages(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media_images,id',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $image = MediaImage::find($id);
            if ($image) {
                $image->delete();
                $count++;
            }
        }

        return back()->with('success', "$count image(s) supprimée(s) avec succès.");
    }

    /**
     * Bulk delete videos
     */
    public function bulkDeleteVideos(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media_videos,id',
        ]);

        $count = MediaVideo::whereIn('id', $request->ids)->count();
        MediaVideo::whereIn('id', $request->ids)->delete();

        return back()->with('success', "$count vidéo(s) supprimée(s) avec succès.");
    }
}
