<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        // Configuration des liens sociaux
        $socialLinks = [
            'youtube' => 'https://youtube.com/@adja-amsetou',
            'facebook' => 'https://facebook.com/adja.amsetou',
            'tiktok' => 'https://tiktok.com/@adja.amsetou',
            'instagram' => 'https://instagram.com/adja.amsetou',
        ];

        // ID de la chaîne YouTube pour l'intégration
        $youtubeChannelId = 'YOUR_YOUTUBE_CHANNEL_ID';

        return view('media', compact('socialLinks', 'youtubeChannelId'));
    }
}
