@extends('layouts.app')

@section('title', $realisation->title)

@if(isset($metaTitle))
    @section('meta_title', $metaTitle)
@endif

@if(isset($metaDescription))
    @section('meta_description', $metaDescription)
@endif

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('realisations.index') }}">Réalisations</a></li>
            @if($realisation->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('realisations.index', ['category' => $realisation->category]) }}">
                        {{ $realisation->category }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active">{{ Str::limit($realisation->title, 40) }}</li>
        </ol>
    </div>
</nav>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Image principale -->
                <div class="position-relative mb-4">
                    @if($realisation->image)
                        <img src="{{ asset('storage/' . $realisation->image) }}"
                             class="img-fluid rounded shadow-sm w-100 main-image"
                             alt="{{ $realisation->title }}"
                             style="max-height: 500px; object-fit: cover; cursor: zoom-in;"
                             onclick="openGallery(0)">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                             style="height: 500px;">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    @endif

                    <!-- Badges -->
                    @if($realisation->is_featured ?? false)
                        <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>Vedette
                        </span>
                    @endif

                    <span class="position-absolute top-0 start-0 m-3 badge bg-primary">
                        {{ $realisation->category }}
                    </span>

                    @php
                        $hasGallery = isset($realisation->gallery) && is_array($realisation->gallery) && count($realisation->gallery) > 0;
                        $totalPhotos = $hasGallery ? count($realisation->gallery) + 1 : 1;
                    @endphp
                    @if($hasGallery)
                        <span class="position-absolute bottom-0 end-0 m-3 badge bg-dark bg-opacity-75">
                            <i class="fas fa-images me-1"></i>{{ $totalPhotos }} photos
                        </span>
                    @endif

                    @if(isset($realisation->views) && $realisation->views > 0)
                        <span class="position-absolute bottom-0 start-0 m-3 badge bg-dark bg-opacity-75">
                            <i class="fas fa-eye me-1"></i>{{ $realisation->views }} vues
                        </span>
                    @endif
                </div>

                <!-- Titre et métadonnées -->
                <div class="mb-4">
                    <h1 class="display-5 fw-bold mb-3">{{ $realisation->title }}</h1>

                    <div class="d-flex flex-wrap gap-3 text-muted mb-3">
                        <span>
                            <i class="far fa-calendar me-2"></i>
                            {{ $realisation->created_at->locale('fr')->isoFormat('D MMMM YYYY') }}
                        </span>
                        <span>
                            <i class="fas fa-tag me-2"></i>
                            {{ $realisation->category }}
                        </span>
                        @if($hasGallery)
                            <span>
                                <i class="fas fa-images me-2"></i>
                                {{ $totalPhotos }} photos
                            </span>
                        @endif
                        @if(isset($realisation->views))
                            <span>
                                <i class="fas fa-eye me-2"></i>
                                {{ $realisation->views }} vues
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Description courte -->
                @if($realisation->short_description)
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>{{ $realisation->short_description }}</strong>
                    </div>
                @endif

                <!-- Description complète -->
                <div class="content-section mb-5">
                    <h3 class="mb-4">
                        <i class="fas fa-align-left text-primary me-2"></i>
                        Description du projet
                    </h3>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="content" style="line-height: 1.8;">
                                {!! $realisation->description !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Galerie d'images -->
                @if($hasGallery)
                    <div class="mb-5">
                        <h3 class="mb-4">
                            <i class="fas fa-images text-primary me-2"></i>
                            Galerie
                            <small class="text-muted">({{ count($realisation->gallery) }} images)</small>
                        </h3>
                        <div class="row g-3">
                            @foreach($realisation->gallery as $index => $image)
                                <div class="col-md-4 col-sm-6">
                                    <div class="gallery-item position-relative overflow-hidden rounded shadow-sm">
                                        <img src="{{ asset('storage/' . $image) }}"
                                             class="img-fluid w-100"
                                             alt="Galerie {{ $loop->iteration }}"
                                             style="height: 250px; object-fit: cover; cursor: zoom-in;"
                                             onclick="openGallery({{ $loop->iteration }})"
                                             loading="lazy">

                                        <!-- Overlay au survol -->
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus fa-2x"></i>
                                        </div>

                                        <!-- Numéro -->
                                        <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark bg-opacity-75">
                                            {{ $loop->iteration }}/{{ count($realisation->gallery) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Vidéo YouTube/Vimeo -->
                @if($realisation->video_url)
                    <div class="mb-5">
                        <h3 class="mb-4">
                            @if(str_contains($realisation->video_url, 'youtube') || str_contains($realisation->video_url, 'youtu.be'))
                                <i class="fab fa-youtube text-danger me-2"></i>
                            @elseif(str_contains($realisation->video_url, 'vimeo'))
                                <i class="fab fa-vimeo text-info me-2"></i>
                            @else
                                <i class="fas fa-video text-primary me-2"></i>
                            @endif
                            Vidéo du projet
                        </h3>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="ratio ratio-16x9">
                                    @php
                                        $videoUrl = $realisation->video_url;
                                        // YouTube
                                        if (str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be')) {
                                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $videoUrl, $match);
                                            $embedUrl = isset($match[1]) ? "https://www.youtube.com/embed/{$match[1]}" : $videoUrl;
                                        }
                                        // Vimeo
                                        elseif (str_contains($videoUrl, 'vimeo.com')) {
                                            preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $match);
                                            $embedUrl = isset($match[1]) ? "https://player.vimeo.com/video/{$match[1]}" : $videoUrl;
                                        }
                                        else {
                                            $embedUrl = $videoUrl;
                                        }
                                    @endphp
                                    <iframe src="{{ $embedUrl }}"
                                            allowfullscreen
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            loading="lazy">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Partage social -->
                <div class="mb-5">
                    <h5 class="mb-3">
                        <i class="fas fa-share-alt text-primary me-2"></i>
                        Partager cette réalisation
                    </h5>
                    <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($realisation->title) }}"
                           target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($realisation->title . ' - ' . url()->current()) }}"
                           target="_blank" rel="noopener noreferrer" class="btn btn-outline-success">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                           target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                            <i class="fab fa-linkedin-in me-2"></i>LinkedIn
                        </a>
                        <button onclick="copyToClipboard()" class="btn btn-outline-secondary">
                            <i class="fas fa-link me-2"></i>Copier le lien
                        </button>
                    </div>
                </div>

                <!-- Navigation -->
                @if(isset($previousRealisation) || isset($nextRealisation))
                    <div class="d-flex justify-content-between flex-wrap gap-2 mb-4">
                        @if(isset($previousRealisation))
                            <a href="{{ route('realisations.show', $previousRealisation->slug) }}"
                               class="btn btn-outline-primary d-flex align-items-center">
                                <i class="fas fa-arrow-left me-2"></i>
                                <div class="text-start">
                                    <small class="d-block">Précédent</small>
                                    <strong>{{ Str::limit($previousRealisation->title, 30) }}</strong>
                                </div>
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if(isset($nextRealisation))
                            <a href="{{ route('realisations.show', $nextRealisation->slug) }}"
                               class="btn btn-outline-primary d-flex align-items-center">
                                <div class="text-end">
                                    <small class="d-block">Suivant</small>
                                    <strong>{{ Str::limit($nextRealisation->title, 30) }}</strong>
                                </div>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Infos -->
                <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informations
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="fas fa-tag text-primary me-2"></i>Catégorie
                                    </span>
                                    <span class="badge bg-primary">{{ $realisation->category }}</span>
                                </div>
                            </li>
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="far fa-calendar text-primary me-2"></i>Date
                                    </span>
                                    <strong>{{ $realisation->created_at->format('d/m/Y') }}</strong>
                                </div>
                            </li>
                            @if($realisation->is_featured ?? false)
                                <li class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">
                                            <i class="fas fa-star text-warning me-2"></i>Statut
                                        </span>
                                        <span class="badge bg-warning text-dark">En vedette</span>
                                    </div>
                                </li>
                            @endif
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="fas fa-images text-primary me-2"></i>Photos
                                    </span>
                                    <strong>{{ $totalPhotos }}</strong>
                                </div>
                            </li>
                            @if($realisation->video_url)
                                <li class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">
                                            <i class="fab fa-youtube text-danger me-2"></i>Vidéo
                                        </span>
                                        <span class="badge bg-danger">Disponible</span>
                                    </div>
                                </li>
                            @endif
                            @if(isset($realisation->views) && $realisation->views > 0)
                                <li class="mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">
                                            <i class="fas fa-eye text-primary me-2"></i>Vues
                                        </span>
                                        <strong>{{ $realisation->views }}</strong>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Similaires -->
                @if(isset($relatedRealisations) && $relatedRealisations->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-layer-group me-2"></i>Projets similaires
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($relatedRealisations as $related)
                                <div class="p-3 border-bottom hover-bg-light">
                                    <a href="{{ route('realisations.show', $related->slug) }}"
                                       class="text-decoration-none text-dark d-block">
                                        @if($related->image)
                                            <div class="position-relative mb-2">
                                                <img src="{{ asset('storage/' . $related->image) }}"
                                                     class="img-fluid rounded w-100"
                                                     alt="{{ $related->title }}"
                                                     style="height: 150px; object-fit: cover;"
                                                     loading="lazy">
                                                @if($related->is_featured ?? false)
                                                    <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        <span class="badge bg-success mb-2">{{ $related->category }}</span>
                                        <h6 class="mb-2">{{ $related->title }}</h6>
                                        <p class="text-muted small mb-2">
                                            {{ Str::limit($related->short_description ?? strip_tags($related->description), 80) }}
                                        </p>
                                        <div class="d-flex justify-content-between text-muted small">
                                            <span>
                                                <i class="far fa-calendar me-1"></i>
                                                {{ $related->created_at->format('d/m/Y') }}
                                            </span>
                                            @php
                                                $relatedHasGallery = isset($related->gallery) && is_array($related->gallery) && count($related->gallery) > 0;
                                            @endphp
                                            @if($relatedHasGallery)
                                                <span>
                                                    <i class="fas fa-images me-1"></i>
                                                    {{ count($related->gallery) + 1 }}
                                                </span>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer bg-white text-center">
                            <a href="{{ route('realisations.index') }}" class="btn btn-sm btn-outline-success">
                                Voir tous les projets <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- CTA -->
                <div class="card border-0 shadow-sm bg-gradient text-white"
                     style="background: linear-gradient(135deg, #2d6a4f 0%, #1e4d35 100%);">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-handshake fa-3x mb-3"></i>
                        <h5 class="card-title">Intéressé par nos projets ?</h5>
                        <p class="card-text">Contactez-nous pour en savoir plus ou pour collaborer</p>
                        <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-envelope me-2"></i>Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Galerie -->
@if($hasGallery || $realisation->image)
<div class="modal fade" id="galleryModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">
                    <i class="fas fa-images me-2"></i>{{ $realisation->title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center position-relative">
                <img id="galleryImage" src="" class="img-fluid" alt="Image"
                     style="max-height: 85vh; max-width: 100%; object-fit: contain;">

                <button class="btn btn-light btn-lg position-absolute start-0 top-50 translate-middle-y ms-3"
                        id="prevBtn" onclick="navigateGallery(-1)" style="opacity: 0.8;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-light btn-lg position-absolute end-0 top-50 translate-middle-y me-3"
                        id="nextBtn" onclick="navigateGallery(1)" style="opacity: 0.8;">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                    <span class="badge bg-dark bg-opacity-75 px-4 py-2 fs-6">
                        <span id="currentImageNumber">1</span> / <span id="totalImagesCount">1</span>
                    </span>
                </div>
            </div>

            <div class="modal-footer border-0 bg-dark p-3" style="overflow-x: auto;">
                <div class="d-flex gap-2" id="thumbnailsContainer"></div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- CTA Bottom -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Découvrez nos autres réalisations</h3>
                <p class="lead text-muted mb-lg-0">
                    Explorez nos projets en agriculture, élevage et artisanat
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('realisations.index') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-th me-2"></i>Tous les projets
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Content HTML */
.content { line-height: 1.8; color: #333; }
.content h1 { font-size: 2rem; margin-top: 2rem; margin-bottom: 1rem; }
.content h2 { font-size: 1.75rem; margin-top: 1.5rem; margin-bottom: 0.875rem; }
.content h3 { font-size: 1.5rem; margin-top: 1.25rem; margin-bottom: 0.75rem; }
.content p { margin-bottom: 1rem; text-align: justify; }
.content ul, .content ol { margin-bottom: 1rem; padding-left: 2rem; }
.content img { max-width: 100%; height: auto; margin: 1rem 0; border-radius: 0.5rem; }
.content blockquote { border-left: 4px solid #198754; padding-left: 1rem; margin: 1.5rem 0; font-style: italic; color: #666; }
.content table { width: 100%; margin: 1rem 0; border-collapse: collapse; }
.content table th, .content table td { padding: 0.75rem; border: 1px solid #dee2e6; }
.content table th { background-color: #f8f9fa; font-weight: 600; }

/* Galerie */
.gallery-item { position: relative; overflow: hidden; border-radius: 0.5rem; transition: transform 0.3s ease; }
.gallery-item:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important; }
.gallery-item img { transition: transform 0.5s ease; }
.gallery-item:hover img { transform: scale(1.1); }
.gallery-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(45,106,79,0.8); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; color: white; z-index: 1; }
.gallery-item:hover .gallery-overlay { opacity: 1; }

/* Thumbnails */
.thumbnail { width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 3px solid transparent; border-radius: 0.25rem; transition: all 0.3s ease; opacity: 0.6; }
.thumbnail:hover { opacity: 1; transform: scale(1.1); }
.thumbnail.active { border-color: #2d6a4f; opacity: 1; }

.hover-bg-light { transition: background-color 0.3s ease; }
.hover-bg-light:hover { background-color: #f8f9fa !important; }

@media (min-width: 992px) { .sticky-top { position: sticky; top: 20px; } }
</style>
@endpush

@push('scripts')
<script>
// Galerie
const galleryImages = [
    @if($realisation->image)
    { url: '{{ asset('storage/' . $realisation->image) }}', alt: '{{ $realisation->title }}' },
    @endif
    @if($hasGallery)
        @foreach($realisation->gallery as $image)
        { url: '{{ asset('storage/' . $image) }}', alt: '{{ $realisation->title }} - {{ $loop->iteration }}' },
        @endforeach
    @endif
];

let currentImageIndex = 0;
const galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));

function initGallery() {
    document.getElementById('totalImagesCount').textContent = galleryImages.length;
    const container = document.getElementById('thumbnailsContainer');
    galleryImages.forEach((img, index) => {
        const thumb = document.createElement('img');
        thumb.src = img.url;
        thumb.className = 'thumbnail' + (index === 0 ? ' active' : '');
        thumb.onclick = () => openGallery(index);
        container.appendChild(thumb);
    });
}

function openGallery(index) {
    currentImageIndex = index;
    updateGalleryImage();
    galleryModal.show();
}

function updateGalleryImage() {
    const img = galleryImages[currentImageIndex];
    document.getElementById('galleryImage').src = img.url;
    document.getElementById('currentImageNumber').textContent = currentImageIndex + 1;
    document.querySelectorAll('.thumbnail').forEach((t, i) => t.classList.toggle('active', i === currentImageIndex));
}

function navigateGallery(dir) {
    currentImageIndex = (currentImageIndex + dir + galleryImages.length) % galleryImages.length;
    updateGalleryImage();
}

function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = event.target.closest('button');
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-2"></i>Copié !';
        btn.classList.replace('btn-outline-secondary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.classList.replace('btn-success', 'btn-outline-secondary');
        }, 2000);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (galleryImages.length > 0) initGallery();
});

document.addEventListener('keydown', (e) => {
    if (document.getElementById('galleryModal').classList.contains('show')) {
        if (e.key === 'ArrowLeft') navigateGallery(-1);
        if (e.key === 'ArrowRight') navigateGallery(1);
        if (e.key === 'Escape') galleryModal.hide();
    }
});
</script>
@endpush
