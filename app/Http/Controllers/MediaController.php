<?php

namespace App\Http\Controllers;

use App\Models\MediaImage;
use App\Models\MediaVideo;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display public media gallery
     */
    public function index()
    {
        // Récupérer toutes les images et vidéos publiées
        $images = MediaImage::published()->latest()->get();
        $videos = MediaVideo::published()->latest()->get();

        return view('media', compact('images', 'videos'));
    }
}
