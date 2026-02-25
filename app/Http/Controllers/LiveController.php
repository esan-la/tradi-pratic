<?php

namespace App\Http\Controllers;

use App\Models\LiveStream;
use Illuminate\Http\Request;

class LiveController extends Controller
{
    /**
     * Page Live principale
     */
    public function index()
    {
        // Live en cours
        $currentLive = LiveStream::getCurrentLive();

        // Prochains lives programmés
        $upcomingStreams = LiveStream::scheduled()
            ->take(6)
            ->get();

        // Rediffusions (lives terminés)
        $replays = LiveStream::ended()
            ->take(6)
            ->get();

        return view('pages.live', compact(
            'currentLive',
            'upcomingStreams',
            'replays'
        ));
    }

    /**
     * Voir un live/replay spécifique
     */
    public function show(LiveStream $liveStream)
    {
        // Autres replays pour suggestions
        $relatedReplays = LiveStream::ended()
            ->where('id', '!=', $liveStream->id)
            ->take(4)
            ->get();

        return view('pages.live-show', compact('liveStream', 'relatedReplays'));
    }
}
