

{{-- <style>
.hover-zoom {
    transition: transform 0.3s ease;
    cursor: pointer;
}
.hover-zoom:hover {
    transform: scale(1.05);
}
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
}
</style>
@endsection --}}





@extends('layouts.app')

@section('title', 'Galerie Média')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-success text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">Galerie Média</h1>
                <p class="lead">Photos et vidéos de nos activités</p>
            </div>
        </div>
    </div>
</section>

<!-- Filter Tabs -->
<section class="py-4 bg-light">
    <div class="container">
        <ul class="nav nav-pills justify-content-center" id="mediaTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button">
                    <i class="fas fa-th me-2"></i>Tout ({{ $images->count() + $videos->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="photos-tab" data-bs-toggle="pill" data-bs-target="#photos" type="button">
                    <i class="fas fa-image me-2"></i>Photos ({{ $images->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="videos-tab" data-bs-toggle="pill" data-bs-target="#videos" type="button">
                    <i class="fas fa-video me-2"></i>Vidéos ({{ $videos->count() }})
                </button>
            </li>
        </ul>
    </div>
</section>

<!-- Media Content -->
<section class="py-5">
    <div class="container">
        <div class="tab-content" id="mediaTabContent">
            <!-- Tout -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <div class="row g-4">
                    <!-- Photos -->
                    @foreach($images as $image)
                        <div class="col-md-4 col-lg-3">
                            <a href="{{ $image->url }}" data-lightbox="gallery" data-title="Photo {{ $loop->iteration }}">
                                <div class="card shadow-sm hover-zoom">
                                    <img src="{{ $image->url }}"
                                         class="card-img-top"
                                         alt="Photo {{ $loop->iteration }}"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">
                                            <i class="fas fa-image me-1"></i>Photo
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                    <!-- Vidéos -->
                    @foreach($videos as $video)
                        <div class="col-md-4 col-lg-3">
                            <div class="card shadow-sm hover-zoom"
                                 data-bs-toggle="modal"
                                 data-bs-target="#videoModal{{ $video->id }}"
                                 style="cursor: pointer;">
                                <div class="position-relative">
                                    <img src="{{ $video->thumbnail }}"
                                         class="card-img-top"
                                         alt="Vidéo {{ $loop->iteration }}"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="bg-danger bg-opacity-75 rounded-circle p-3">
                                            <i class="fas fa-play text-white fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <small class="text-muted">
                                        <i class="fas fa-video me-1"></i>Vidéo
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Photos uniquement -->
            <div class="tab-pane fade" id="photos" role="tabpanel">
                <div class="row g-4">
                    @forelse($images as $image)
                        <div class="col-md-4 col-lg-3">
                            <a href="{{ $image->url }}" data-lightbox="photos-gallery">
                                <div class="card shadow-sm hover-zoom">
                                    <img src="{{ $image->url }}"
                                         class="card-img-top"
                                         alt="Photo {{ $loop->iteration }}"
                                         style="height: 200px; object-fit: cover;">
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune photo disponible pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Vidéos uniquement -->
            <div class="tab-pane fade" id="videos" role="tabpanel">
                <div class="row g-4">
                    @forelse($videos as $video)
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm hover-zoom"
                                 data-bs-toggle="modal"
                                 data-bs-target="#videoModal{{ $video->id }}"
                                 style="cursor: pointer;">
                                <div class="position-relative">
                                    <img src="{{ $video->thumbnail }}"
                                         class="card-img-top"
                                         alt="Vidéo {{ $loop->iteration }}"
                                         style="height: 250px; object-fit: cover;">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="bg-danger bg-opacity-75 rounded-circle p-4">
                                            <i class="fas fa-play text-white fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-video fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune vidéo disponible pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Réseaux sociaux -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="mb-3">Suivez-nous sur les réseaux sociaux</h2>
                <p class="text-muted">Restez connectés pour plus de contenus</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <a href="{{ config('contact.social.facebook') }}" class="text-decoration-none" target="_blank">
                    <div class="card shadow-sm text-center hover-lift">
                        <div class="card-body">
                            <i class="fab fa-facebook fa-3x text-primary mb-3"></i>
                            <h5>Facebook</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ config('contact.social.youtube') }}" class="text-decoration-none" target="_blank">
                    <div class="card shadow-sm text-center hover-lift">
                        <div class="card-body">
                            <i class="fab fa-youtube fa-3x text-danger mb-3"></i>
                            <h5>YouTube</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ config('contact.social.tiktok') }}" class="text-decoration-none" target="_blank">
                    <div class="card shadow-sm text-center hover-lift">
                        <div class="card-body">
                            <i class="fab fa-tiktok fa-3x text-dark mb-3"></i>
                            <h5>TikTok</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ config('contact.social.instagram') }}" class="text-decoration-none" target="_blank">
                    <div class="card shadow-sm text-center hover-lift">
                        <div class="card-body">
                            <i class="fab fa-instagram fa-3x text-danger mb-3"></i>
                            <h5>Instagram</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Video Modals -->
@foreach($videos as $video)
    <div class="modal fade" id="videoModal{{ $video->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vidéo {{ $loop->iteration }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    @if($video->is_youtube || $video->is_vimeo)
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $video->embed_url }}"
                                    allowfullscreen
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                    @else
                        <video controls class="w-100">
                            <source src="{{ $video->url }}" type="video/mp4">
                            Votre navigateur ne supporte pas la vidéo.
                        </video>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Lightbox CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">

<style>
.hover-zoom {
    transition: transform 0.3s ease;
    cursor: pointer;
}
.hover-zoom:hover {
    transform: scale(1.05);
}
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
}
</style>
@endsection

@push('scripts')
<!-- Lightbox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script>
lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'albumLabel': 'Image %1 sur %2'
});
</script>
@endpush
