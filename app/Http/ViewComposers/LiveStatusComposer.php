<?php

namespace App\Http\ViewComposers;

use App\Models\LiveStream;
use Illuminate\View\View;

class LiveStatusComposer
{
    public function compose(View $view): void
    {
        // Vérifier que la table existe avant de requêter
        try {
            $view->with('hasActiveLive', LiveStream::hasActiveLive());
            $view->with('currentLive', LiveStream::getCurrentLive());
        } catch (\Exception $e) {
            // Table pas encore migrée ou erreur
            $view->with('hasActiveLive', false);
            $view->with('currentLive', null);
        }
    }
}
