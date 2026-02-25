{{-- resources/views/pages/live.blade.php --}}

@extends('layouts.app')

@section('title', 'En Direct - Live')

@section('meta_description', 'Suivez Adja Amsetou en direct depuis Komsilga. Consultations, prières et enseignements traditionnels en live.')

@section('content')

<!-- ====================== -->
<!-- HEADER LIVE             -->
<!-- ====================== -->
<section class="live-page-header text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center flex-wrap gap-3 mb-2">
                    @if($currentLive)
                        <span class="live-badge-header">
                            <span class="live-dot-header"></span>
                            EN DIRECT
                        </span>
                    @else
                        <span class="badge bg-secondary fs-6 px-3 py-2">
                            <i class="fas fa-video me-1"></i> HORS DIRECT
                        </span>
                    @endif
                    <h1 class="h3 fw-bold mb-0">TradiPratic Live</h1>
                </div>
                <p class="text-white-50 mb-0">
                    Suivez nos émissions en direct sur les pratiques traditionnelles du Burkina Faso
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ config('services.youtube.subscribe_url') }}"
                   target="_blank"
                   class="btn btn-danger btn-lg">
                    <i class="fab fa-youtube me-2"></i>S'abonner
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ====================== -->
<!-- LIVE EN COURS           -->
<!-- ====================== -->
@if($currentLive)
    <section class="live-player-section bg-black">
        <div class="container-fluid px-0">
            <div class="row g-0">

                <!-- Player Vidéo -->
                <div class="col-lg-{{ $currentLive->chat_enabled ? '8' : '12' }}">
                    <div class="ratio ratio-16x9">
                        <iframe
                            src="{{ $currentLive->embed_url }}?autoplay=1&mute=0&rel=0"
                            title="{{ $currentLive->title }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <!-- Infos sous la vidéo -->
                    <div class="bg-dark text-white p-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-danger">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem; animation: liveBlink 1s infinite;"></i>
                                        EN DIRECT
                                    </span>
                                    @if($currentLive->category)
                                        <span class="badge bg-success">{{ $currentLive->category_label }}</span>
                                    @endif
                                </div>
                                <h4 class="fw-bold mb-1">{{ $currentLive->title }}</h4>
                                @if($currentLive->description)
                                    <p class="text-white-50 mb-0">{{ $currentLive->description }}</p>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-light btn-sm" onclick="shareLive()">
                                    <i class="fas fa-share-alt me-1"></i>Partager
                                </button>
                                <a href="{{ $currentLive->youtube_watch_url }}"
                                   target="_blank"
                                   class="btn btn-outline-danger btn-sm">
                                    <i class="fab fa-youtube me-1"></i>Ouvrir sur YouTube
                                </a>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-3 gap-4 text-white-50 small">
                            @if($currentLive->started_at)
                                <span>
                                    <i class="fas fa-clock me-1"></i>
                                    Démarré {{ $currentLive->started_at->diffForHumans() }}
                                </span>
                            @endif
                            @if($currentLive->viewer_count > 0)
                                <span>
                                    <i class="fas fa-eye me-1"></i>
                                    {{ number_format($currentLive->viewer_count, 0, ',', ' ') }} spectateurs
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Chat en direct -->
                @if($currentLive->chat_enabled && $currentLive->chat_embed_url)
                    <div class="col-lg-4 d-none d-lg-block">
                        <div class="live-chat-container bg-dark" style="height: 100%;">
                            <div class="live-chat-header p-3 border-bottom border-secondary">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-comments me-2 text-success"></i>Chat en direct
                                </h6>
                            </div>
                            <iframe
                                src="{{ $currentLive->chat_embed_url }}"
                                width="100%"
                                style="height: calc(100% - 50px); min-height: 400px; border: none;"
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <!-- Bouton Chat Mobile -->
        @if($currentLive->chat_enabled)
            <div class="d-lg-none bg-dark text-center py-3">
                <a href="{{ $currentLive->youtube_watch_url }}"
                   target="_blank"
                   class="btn btn-outline-light">
                    <i class="fas fa-comments me-2"></i>Rejoindre le chat sur YouTube
                </a>
            </div>
        @endif
    </section>

@else
    <!-- ====================== -->
    <!-- PAS DE LIVE EN COURS    -->
    <!-- ====================== -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center py-5">
                <div class="mb-4">
                    <div class="offline-icon mx-auto">
                        <i class="fas fa-satellite-dish fa-3x text-secondary"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-3">Aucune diffusion en cours</h3>
                <p class="text-muted mb-4 col-lg-6 mx-auto">
                    Nous ne sommes pas en direct pour le moment. Consultez le programme ci-dessous
                    ou abonnez-vous pour être notifié des prochaines émissions.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ config('services.youtube.subscribe_url') }}"
                       target="_blank"
                       class="btn btn-danger btn-lg">
                        <i class="fab fa-youtube me-2"></i>S'abonner à la chaîne
                    </a>
                    @if($replays->count() > 0)
                        <a href="#replays" class="btn btn-outline-success btn-lg">
                            <i class="fas fa-play-circle me-2"></i>Voir les rediffusions
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif

<!-- ====================== -->
<!-- PROCHAINS LIVES         -->
<!-- ====================== -->
@if($upcomingStreams->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-calendar-alt text-success me-2"></i>Prochaines émissions
                </h3>
                <span class="badge bg-info fs-6">{{ $upcomingStreams->count() }} programmée(s)</span>
            </div>

            <div class="row g-4">
                @foreach($upcomingStreams as $stream)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100 upcoming-card">
                            <!-- Thumbnail -->
                            <div class="position-relative">
                                <img src="{{ $stream->thumbnail_url }}"
                                     alt="{{ $stream->title }}"
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover;"
                                     onerror="this.src='https://via.placeholder.com/640x360/198754/ffffff?text=Live+TradiPratic'">

                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-info">
                                        <i class="fas fa-clock me-1"></i>Programmé
                                    </span>
                                </div>

                                @if($stream->category)
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-success">{{ $stream->category_label }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $stream->title }}</h5>

                                @if($stream->description)
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($stream->description, 100) }}
                                    </p>
                                @endif

                                <!-- Countdown -->
                                <div class="upcoming-date mt-3">
                                    <div class="d-flex align-items-center">
                                        <div class="date-icon bg-success bg-opacity-10 text-success rounded p-2 me-3">
                                            <i class="fas fa-calendar-day fa-lg"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block">
                                                {{ $stream->scheduled_at->locale('fr')->isoFormat('dddd D MMMM') }}
                                            </strong>
                                            <span class="text-muted small">
                                                à {{ $stream->scheduled_at->format('H:i') }} (GMT)
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Timer -->
                                    <div class="countdown-timer mt-2 text-center"
                                         data-target="{{ $stream->scheduled_at->toIso8601String() }}">
                                        <small class="text-success fw-semibold">
                                            <i class="fas fa-hourglass-half me-1"></i>
                                            <span class="countdown-text">Chargement...</span>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-top-0 pb-3">
                                <div class="d-flex gap-2">
                                    @if($stream->youtube_watch_url !== '#')
                                        <a href="{{ $stream->youtube_watch_url }}"
                                           target="_blank"
                                           class="btn btn-outline-danger btn-sm flex-fill">
                                            <i class="fas fa-bell me-1"></i>Rappel YouTube
                                        </a>
                                    @endif
                                    <button class="btn btn-outline-success btn-sm"
                                            onclick="shareStream('{{ $stream->title }}', '{{ route('live') }}')">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- ====================== -->
<!-- REDIFFUSIONS            -->
<!-- ====================== -->
@if($replays->count() > 0)
    <section class="py-5 bg-light" id="replays">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="fas fa-play-circle text-success me-2"></i>Rediffusions
                </h3>
                <a href="{{ config('services.youtube.channel_url') }}/videos"
                   target="_blank"
                   class="btn btn-outline-danger btn-sm">
                    <i class="fab fa-youtube me-1"></i>Toutes les vidéos
                </a>
            </div>

            <div class="row g-4">
                @foreach($replays as $replay)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100 replay-card">
                            <!-- Player embed -->
                            <div class="ratio ratio-16x9">
                                <iframe
                                    src="{{ $replay->embed_url }}"
                                    title="{{ $replay->title }}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    loading="lazy">
                                </iframe>
                            </div>

                            <div class="card-body">
                                <div class="d-flex gap-2 mb-2">
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-check-circle me-1"></i>Terminé
                                    </span>
                                    @if($replay->category)
                                        <span class="badge bg-success-subtle text-success">
                                            {{ $replay->category_label }}
                                        </span>
                                    @endif
                                </div>

                                <h6 class="card-title fw-bold">{{ $replay->title }}</h6>

                                <div class="d-flex justify-content-between text-muted small mt-2">
                                    @if($replay->ended_at)
                                        <span>
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $replay->ended_at->locale('fr')->isoFormat('D MMM YYYY') }}
                                        </span>
                                    @endif
                                    @if($replay->duration)
                                        <span>
                                            <i class="fas fa-clock me-1"></i>{{ $replay->duration }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- ====================== -->
<!-- SECTION S'ABONNER       -->
<!-- ====================== -->
<section class="py-5 subscribe-section text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <i class="fab fa-youtube fa-3x mb-3" style="color: #ff0000;"></i>
                <h3 class="fw-bold mb-3">Ne manquez aucune émission !</h3>
                <p class="mb-4 opacity-75">
                    Abonnez-vous à notre chaîne YouTube et activez la cloche 🔔
                    pour recevoir les notifications de nos prochains lives.
                </p>
                <a href="{{ config('services.youtube.subscribe_url') }}"
                   target="_blank"
                   class="btn btn-danger btn-lg px-5">
                    <i class="fab fa-youtube me-2"></i>S'abonner gratuitement
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Header */
.live-page-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
}

.live-badge-header {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #dc3545;
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 800;
    font-size: 0.9rem;
    letter-spacing: 1px;
}

.live-dot-header {
    width: 10px;
    height: 10px;
    background: white;
    border-radius: 50%;
    animation: liveBlink 1s infinite;
}

/* Offline icon */
.offline-icon {
    width: 120px;
    height: 120px;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Cards */
.upcoming-card {
    transition: transform 0.3s;
    border-radius: 12px;
    overflow: hidden;
}

.upcoming-card:hover {
    transform: translateY(-5px);
}

.replay-card {
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s;
}

.replay-card:hover {
    transform: translateY(-3px);
}

/* Subscribe section */
.subscribe-section {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
}

/* Chat */
.live-chat-container {
    display: flex;
    flex-direction: column;
}

/* Countdown */
.countdown-timer {
    background: #f0fdf4;
    border-radius: 8px;
    padding: 6px 12px;
}

@media (max-width: 991.98px) {
    .live-page-header h1 {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// ============================
// PARTAGE
// ============================
function shareLive() {
    shareStream(
        '{{ $currentLive->title ?? "TradiPratic Live" }}',
        '{{ url()->current() }}'
    );
}

function shareStream(title, url) {
    const shareData = {
        title: title,
        text: '🔴 ' + title + ' - Regardez en direct sur TradiPratic !',
        url: url,
    };

    if (navigator.share) {
        navigator.share(shareData).catch(() => {});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('✅ Lien copié dans le presse-papier !');
        });
    }
}

// ============================
// COUNTDOWN TIMERS
// ============================
document.addEventListener('DOMContentLoaded', function() {
    const countdowns = document.querySelectorAll('.countdown-timer');

    countdowns.forEach(function(el) {
        const target = new Date(el.dataset.target).getTime();
        const textEl = el.querySelector('.countdown-text');

        function update() {
            const now = new Date().getTime();
            const diff = target - now;

            if (diff <= 0) {
                textEl.innerHTML = '<span class="text-danger">Devrait commencer bientôt !</span>';
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            let text = '';
            if (days > 0) text += days + 'j ';
            text += hours + 'h ' + minutes + 'min ' + seconds + 's';

            textEl.textContent = 'Dans ' + text;
        }

        update();
        setInterval(update, 1000);
    });
});

// ============================
// VÉRIFICATION LIVE AUTO (toutes les 60s)
// ============================
@if(!$currentLive)
setInterval(function() {
    fetch('{{ route("live") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.text())
    .then(html => {
        if (html.includes('live-player-section')) {
            // Un live a commencé, recharger la page
            location.reload();
        }
    })
    .catch(() => {});
}, 60000);
@endif
</script>
@endpush
