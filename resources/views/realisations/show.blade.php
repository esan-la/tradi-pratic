@extends('layouts.app')

@section('title', $realisation->title)

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('realisations') }}">Réalisations</a></li>
            <li class="breadcrumb-item active">{{ $realisation->title }}</li>
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
                             onclick="openGallery(0)"
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    @endif

                    <!-- Badge vedette si applicable -->
                    @if($realisation->is_featured)
                        <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>Vedette
                        </span>
                    @endif

                    <!-- Badge catégorie -->
                    <span class="position-absolute top-0 start-0 m-3 badge bg-primary">
                        {{ $realisation->category }}
                    </span>

                    <!-- Compteur photos -->
                    @if($realisation->hasGallery())
                        <span class="position-absolute bottom-0 end-0 m-3 badge bg-dark bg-opacity-75">
                            <i class="fas fa-images me-1"></i>
                            {{ $realisation->gallery_count + 1 }} photos
                        </span>
                    @endif
                </div>

                <!-- Titre et métadonnées -->
                <div class="mb-4">
                    <h1 class="display-5 fw-bold mb-3">{{ $realisation->title }}</h1>

                    <div class="d-flex flex-wrap gap-3 text-muted mb-3">
                        <span>
                            <i class="far fa-calendar me-2"></i>
                            {{ $realisation->created_at->format('d F Y') }}
                        </span>
                        <span>
                            <i class="fas fa-tag me-2"></i>
                            {{ $realisation->category }}
                        </span>
                        @if($realisation->hasGallery())
                            <span>
                                <i class="fas fa-images me-2"></i>
                                {{ $realisation->gallery_count + 1 }} photos
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="content-section mb-5">
                    <h3 class="mb-4">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Description
                    </h3>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="lead" style="line-height: 1.8; text-align: justify;">
                                {!! nl2br(e($realisation->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Galerie d'images -->
                @if($realisation->hasGallery())
                    <div class="mb-5">
                        <h3 class="mb-4">
                            <i class="fas fa-images text-primary me-2"></i>
                            Galerie
                            <small class="text-muted">({{ $realisation->gallery_count }} {{ $realisation->gallery_count > 1 ? 'images' : 'image' }})</small>
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
                                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                                             loading="lazy">

                                        <!-- Overlay au survol -->
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus fa-2x"></i>
                                        </div>

                                        <!-- Numéro de l'image -->
                                        <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark bg-opacity-75">
                                            {{ $loop->iteration }}/{{ $realisation->gallery_count }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Vidéo YouTube -->
                @if($realisation->video_url)
                    <div class="mb-5">
                        <h3 class="mb-4">
                            <i class="fab fa-youtube text-danger me-2"></i>
                            Vidéo du projet
                        </h3>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="ratio ratio-16x9">
                                    <iframe
                                        src="{{ str_replace('watch?v=', 'embed/', $realisation->video_url) }}"
                                        allowfullscreen
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        loading="lazy">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Boutons de partage -->
                <div class="mb-5">
                    <h5 class="mb-3">
                        <i class="fas fa-share-alt text-primary me-2"></i>
                        Partager cette réalisation
                    </h5>
                    <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank"
                           class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($realisation->title) }}"
                           target="_blank"
                           class="btn btn-outline-info">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($realisation->title . ' - ' . url()->current()) }}"
                           target="_blank"
                           class="btn btn-outline-success">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                           target="_blank"
                           class="btn btn-outline-primary">
                            <i class="fab fa-linkedin-in me-2"></i>LinkedIn
                        </a>
                        <button onclick="copyToClipboard()" class="btn btn-outline-secondary">
                            <i class="fas fa-link me-2"></i>Copier le lien
                        </button>
                    </div>
                </div>

                <!-- Navigation précédent/suivant -->
                @if(isset($previousRealisation) || isset($nextRealisation))
                    <div class="d-flex justify-content-between mb-4">
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
                <!-- Informations du projet -->
                <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informations du projet
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="text-muted">
                                        <i class="fas fa-tag text-primary me-2"></i>Catégorie
                                    </span>
                                    <span class="badge bg-primary">{{ $realisation->category }}</span>
                                </div>
                            </li>
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="text-muted">
                                        <i class="far fa-calendar text-primary me-2"></i>Date
                                    </span>
                                    <strong>{{ $realisation->created_at->format('d/m/Y') }}</strong>
                                </div>
                            </li>
                            @if($realisation->is_featured)
                                <li class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span class="text-muted">
                                            <i class="fas fa-star text-warning me-2"></i>Statut
                                        </span>
                                        <span class="badge bg-warning text-dark">En vedette</span>
                                    </div>
                                </li>
                            @endif
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="text-muted">
                                        <i class="fas fa-images text-primary me-2"></i>Photos
                                    </span>
                                    <strong>{{ $realisation->hasGallery() ? $realisation->gallery_count + 1 : 1 }}</strong>
                                </div>
                            </li>
                            @if($realisation->video_url)
                                <li class="mb-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span class="text-muted">
                                            <i class="fab fa-youtube text-danger me-2"></i>Vidéo
                                        </span>
                                        <span class="badge bg-danger">Disponible</span>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Réalisations similaires -->
                @if($relatedRealisations->count() > 0)
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
                                                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                                                     loading="lazy">
                                                @if($related->is_featured)
                                                    <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        <span class="badge bg-success mb-2">{{ $related->category }}</span>
                                        <h6 class="mb-2">{{ $related->title }}</h6>
                                        <p class="text-muted small mb-2">
                                            {{ Str::limit($related->description, 80) }}
                                        </p>
                                        <div class="d-flex justify-content-between text-muted small">
                                            <span>
                                                <i class="far fa-calendar me-1"></i>
                                                {{ $related->created_at->format('d/m/Y') }}
                                            </span>
                                            @if($related->hasGallery())
                                                <span>
                                                    <i class="fas fa-images me-1"></i>
                                                    {{ $related->gallery_count + 1 }} photos
                                                </span>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer bg-white text-center">
                            <a href="{{ route('realisations') }}" class="btn btn-sm btn-outline-success">
                                Voir tous les projets <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Call to Action -->
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

<!-- Modal Galerie Amélioré -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">
                    <i class="fas fa-images me-2"></i>
                    <span id="galleryTitle">{{ $realisation->title }}</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center position-relative">
                <!-- Image principale -->
                <img id="galleryImage"
                     src=""
                     class="img-fluid"
                     alt="Image en grand"
                     style="max-height: 85vh; max-width: 100%; object-fit: contain;">

                <!-- Boutons de navigation -->
                <button class="btn btn-light btn-lg position-absolute start-0 top-50 translate-middle-y ms-3"
                        id="prevBtn"
                        onclick="navigateGallery(-1)"
                        style="opacity: 0.8;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-light btn-lg position-absolute end-0 top-50 translate-middle-y me-3"
                        id="nextBtn"
                        onclick="navigateGallery(1)"
                        style="opacity: 0.8;">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Compteur -->
                <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                    <span class="badge bg-dark bg-opacity-75 px-4 py-2 fs-6">
                        <span id="currentImageNumber">1</span> / <span id="totalImages">1</span>
                    </span>
                </div>

                <!-- Loader -->
                <div id="imageLoader" class="position-absolute top-50 start-50 translate-middle d-none">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>

            <!-- Miniatures -->
            <div class="modal-footer border-0 bg-dark p-3" style="overflow-x: auto;">
                <div class="d-flex gap-2" id="thumbnailsContainer">
                    <!-- Les miniatures seront insérées ici par JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
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
                <a href="{{ route('realisations') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-th me-2"></i>Tous les projets
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Amélioration qualité images */
    img {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        -ms-interpolation-mode: nearest-neighbor;
    }

    .main-image,
    .gallery-item img {
        image-rendering: auto;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
    }

    /* Galerie */
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
    }

    .gallery-item img {
        transition: transform 0.5s ease;
        will-change: transform;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(45, 106, 79, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: white;
        z-index: 1;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    /* Modal */
    .modal-fullscreen .modal-body {
        background: #000;
    }

    #galleryImage {
        transition: opacity 0.3s ease;
    }

    /* Miniatures */
    #thumbnailsContainer {
        white-space: nowrap;
    }

    .thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
        border: 3px solid transparent;
        border-radius: 0.25rem;
        transition: all 0.3s ease;
        opacity: 0.6;
    }

    .thumbnail:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    .thumbnail.active {
        border-color: #2d6a4f;
        opacity: 1;
    }

    /* Hover effects */
    .hover-bg-light {
        transition: background-color 0.3s ease;
    }

    .hover-bg-light:hover {
        background-color: #f8f9fa !important;
    }

    /* Sticky sidebar */
    @media (min-width: 992px) {
        .sticky-top {
            position: sticky;
            top: 20px;
            z-index: 1020;
        }
    }

    /* Navigation buttons */
    #prevBtn, #nextBtn {
        transition: all 0.3s ease;
    }

    #prevBtn:hover, #nextBtn:hover {
        opacity: 1 !important;
        transform: translateY(-50%) scale(1.1);
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        animation: fadeIn 0.5s ease-out;
    }

    /* Responsive iframe */
    .ratio iframe {
        border: none;
    }

    /* Amélioration mobile */
    @media (max-width: 768px) {
        #prevBtn, #nextBtn {
            width: 40px;
            height: 40px;
            font-size: 14px;
        }

        .thumbnail {
            width: 60px;
            height: 60px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Données de la galerie
    const galleryImages = [
        @if($realisation->image)
        {
            url: '{{ asset('storage/' . $realisation->image) }}',
            alt: '{{ $realisation->title }} - Image principale'
        },
        @endif
        @if($realisation->hasGallery())
            @foreach($realisation->gallery as $image)
            {
                url: '{{ asset('storage/' . $image) }}',
                alt: '{{ $realisation->title }} - Galerie {{ $loop->iteration }}'
            },
            @endforeach
        @endif
    ];

    let currentImageIndex = 0;
    const galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));
    const galleryImage = document.getElementById('galleryImage');
    const imageLoader = document.getElementById('imageLoader');
    const currentImageNumber = document.getElementById('currentImageNumber');
    const totalImages = document.getElementById('totalImages');
    const thumbnailsContainer = document.getElementById('thumbnailsContainer');

    // Initialiser la galerie
    function initGallery() {
        totalImages.textContent = galleryImages.length;

        // Créer les miniatures
        galleryImages.forEach((img, index) => {
            const thumb = document.createElement('img');
            thumb.src = img.url;
            thumb.alt = img.alt;
            thumb.className = 'thumbnail' + (index === 0 ? ' active' : '');
            thumb.onclick = () => openGallery(index);
            thumbnailsContainer.appendChild(thumb);
        });
    }

    // Ouvrir la galerie à un index spécifique
    function openGallery(index) {
        currentImageIndex = index;
        updateGalleryImage();
        galleryModal.show();
    }

    // Mettre à jour l'image affichée
    function updateGalleryImage() {
        if (galleryImages.length === 0) return;

        // Afficher le loader
        imageLoader.classList.remove('d-none');
        galleryImage.style.opacity = '0.3';

        const img = galleryImages[currentImageIndex];

        // Précharger l'image
        const preloadImg = new Image();
        preloadImg.onload = function() {
            galleryImage.src = img.url;
            galleryImage.alt = img.alt;
            galleryImage.style.opacity = '1';
            imageLoader.classList.add('d-none');
        };
        preloadImg.src = img.url;

        // Mettre à jour le compteur
        currentImageNumber.textContent = currentImageIndex + 1;

        // Mettre à jour les miniatures
        document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
            thumb.classList.toggle('active', index === currentImageIndex);
        });

        // Scroller vers la miniature active
        const activeThumbnail = thumbnailsContainer.querySelector('.thumbnail.active');
        if (activeThumbnail) {
            activeThumbnail.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }

        // Gérer les boutons de navigation
        document.getElementById('prevBtn').disabled = currentImageIndex === 0;
        document.getElementById('nextBtn').disabled = currentImageIndex === galleryImages.length - 1;
    }

    // Navigation dans la galerie
    function navigateGallery(direction) {
        currentImageIndex += direction;

        // Boucler sur les images
        if (currentImageIndex < 0) {
            currentImageIndex = galleryImages.length - 1;
        } else if (currentImageIndex >= galleryImages.length) {
            currentImageIndex = 0;
        }

        updateGalleryImage();
    }

    // Navigation au clavier
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('galleryModal').classList.contains('show')) {
            if (e.key === 'ArrowLeft') {
                navigateGallery(-1);
            } else if (e.key === 'ArrowRight') {
                navigateGallery(1);
            } else if (e.key === 'Escape') {
                galleryModal.hide();
            }
        }
    });

    // Fonction pour copier le lien
    function copyToClipboard() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(function() {
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check me-2"></i>Copié !';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');

            setTimeout(function() {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        }, function() {
            alert('Erreur lors de la copie du lien');
        });
    }

    // Initialiser au chargement
    document.addEventListener('DOMContentLoaded', function() {
        if (galleryImages.length > 0) {
            initGallery();
        }
    });
</script>
@endpush
