@extends('layouts.app')

@section('title', $recipe->title)

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
            <li class="breadcrumb-item"><a href="{{ route('recipes.index') }}">Recettes</a></li>
            @if($recipe->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('recipes.index', ['category' => $recipe->category]) }}">
                        {{ $recipe->category }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active">{{ Str::limit($recipe->title, 40) }}</li>
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
                    @if($recipe->image)
                        <img src="{{ asset('storage/' . $recipe->image) }}"
                             class="img-fluid rounded shadow-sm w-100"
                             alt="{{ $recipe->title }}"
                             style="max-height: 500px; object-fit: cover; cursor: zoom-in;"
                             onclick="openGallery(0)">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                             style="height: 400px;">
                            <i class="fas fa-utensils fa-5x text-muted"></i>
                        </div>
                    @endif

                    <!-- Badges sur l'image -->
                    @if($recipe->is_featured ?? false)
                        <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>Vedette
                        </span>
                    @endif

                    @if($recipe->category)
                        <span class="position-absolute top-0 start-0 m-3 badge bg-primary">
                            {{ $recipe->category }}
                        </span>
                    @endif

                    @php
                        $hasGallery = isset($recipe->gallery) && is_array($recipe->gallery) && count($recipe->gallery) > 0;
                        $totalPhotos = $hasGallery ? count($recipe->gallery) + 1 : 1;
                    @endphp
                    @if($hasGallery)
                        <span class="position-absolute bottom-0 end-0 m-3 badge bg-dark bg-opacity-75">
                            <i class="fas fa-images me-1"></i>{{ $totalPhotos }} photos
                        </span>
                    @endif

                    @if(isset($recipe->views) && $recipe->views > 0)
                        <span class="position-absolute bottom-0 start-0 m-3 badge bg-dark bg-opacity-75">
                            <i class="fas fa-eye me-1"></i>{{ $recipe->views }} vues
                        </span>
                    @endif
                </div>

                <!-- Titre -->
                <h1 class="display-5 fw-bold mb-4">{{ $recipe->title }}</h1>

                <!-- Info Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="card text-center h-100 shadow-sm">
                            <div class="card-body py-3">
                                <i class="far fa-clock fa-2x text-primary mb-2"></i>
                                <h6 class="mb-1 small">Préparation</h6>
                                <strong class="fs-5">{{ $recipe->prep_time ?? 0 }}</strong>
                                <small class="d-block">min</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card text-center h-100 shadow-sm">
                            <div class="card-body py-3">
                                <i class="fas fa-fire fa-2x text-danger mb-2"></i>
                                <h6 class="mb-1 small">Cuisson</h6>
                                <strong class="fs-5">{{ $recipe->cook_time ?? 0 }}</strong>
                                <small class="d-block">min</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card text-center h-100 shadow-sm">
                            <div class="card-body py-3">
                                <i class="fas fa-users fa-2x text-success mb-2"></i>
                                <h6 class="mb-1 small">Portions</h6>
                                <strong class="fs-5">{{ $recipe->servings ?? 1 }}</strong>
                                <small class="d-block">pers.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card text-center h-100 shadow-sm">
                            <div class="card-body py-3">
                                <i class="fas fa-signal fa-2x text-warning mb-2"></i>
                                <h6 class="mb-1 small">Difficulté</h6>
                                <span class="badge bg-{{ $recipe->difficulty == 'Facile' ? 'success' : ($recipe->difficulty == 'Moyen' ? 'warning' : 'danger') }} fs-6 mt-1">
                                    {{ $recipe->difficulty ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description courte -->
                @if($recipe->short_description)
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>{{ $recipe->short_description }}</strong>
                    </div>
                @endif

                <!-- SECTION INGRÉDIENTS -->
                <div class="card shadow-sm mb-5">
                    <div class="card-header bg-success text-white py-3">
                        <h3 class="mb-0 h4">
                            <i class="fas fa-shopping-basket me-2"></i>Ingrédients
                            <small class="ms-2">({{ count($recipe->ingredients) }})</small>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-9">
                                <ul class="list-unstyled ingredient-list">
                                    @foreach($recipe->ingredients as $index => $ingredient)
                                        <li class="mb-3 py-2 border-bottom" id="ingredient-{{ $index }}">
                                            <label class="d-flex align-items-start cursor-pointer">
                                                <input type="checkbox" class="form-check-input me-3 mt-1"
                                                       onchange="toggleIngredient({{ $index }})">
                                                <span class="ingredient-text flex-grow-1">{{ $ingredient }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-lg-3">
                                <!-- Calculateur portions (optionnel) -->
                                <div class="card bg-light sticky-top" style="top: 20px;">
                                    <div class="card-body">
                                        <label class="form-label small fw-bold">Ajuster les portions</label>
                                        <div class="input-group mb-2">
                                            <button class="btn btn-outline-secondary" type="button" onclick="adjustServings(-1)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" id="currentServings" class="form-control text-center fw-bold"
                                                   value="{{ $recipe->servings ?? 1 }}" min="1" max="99" readonly>
                                            <button class="btn btn-outline-secondary" type="button" onclick="adjustServings(1)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted d-block text-center">
                                            Original: {{ $recipe->servings ?? 1 }} pers.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION INSTRUCTIONS -->
                <div class="card shadow-sm mb-5">
                    <div class="card-header bg-primary text-white py-3">
                        <h3 class="mb-0 h4">
                            <i class="fas fa-list-ol me-2"></i>Préparation
                            <small class="ms-2">({{ count($recipe->instructions) }} étapes)</small>
                        </h3>
                    </div>
                    <div class="card-body">
                        <ol class="custom-steps">
                            @foreach($recipe->instructions as $index => $instruction)
                                <li class="mb-4 pb-4 {{ $loop->last ? '' : 'border-bottom' }}">
                                    <div class="d-flex gap-3">
                                        <div class="step-number bg-primary text-white flex-shrink-0">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-0 step-text">{{ $instruction }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <button class="btn btn-sm btn-outline-success rounded-circle"
                                                    onclick="toggleStep(this)"
                                                    title="Marquer comme terminé">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                <!-- Description / Notes -->
                @if($recipe->description)
                    <div class="card shadow-sm mb-5">
                        <div class="card-header py-3">
                            <h4 class="mb-0">
                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                Notes et Conseils
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="content" style="line-height: 1.8;">
                                {!! $recipe->description !!}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Galerie -->
                @if($hasGallery)
                    <div class="mb-5">
                        <h4 class="mb-4">
                            <i class="fas fa-images text-primary me-2"></i>
                            Galerie
                            <small class="text-muted">({{ count($recipe->gallery) }} images)</small>
                        </h4>
                        <div class="row g-3">
                            @foreach($recipe->gallery as $index => $image)
                                <div class="col-md-4 col-sm-6">
                                    <div class="gallery-item position-relative overflow-hidden rounded shadow-sm">
                                        <img src="{{ asset('storage/' . $image) }}"
                                             class="img-fluid w-100"
                                             alt="Galerie {{ $loop->iteration }}"
                                             style="height: 250px; object-fit: cover; cursor: zoom-in;"
                                             onclick="openGallery({{ $loop->iteration }})"
                                             loading="lazy">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Vidéo -->
                @if($recipe->video_url)
                    <div class="mb-5">
                        <h4 class="mb-4">
                            @if(str_contains($recipe->video_url, 'youtube') || str_contains($recipe->video_url, 'youtu.be'))
                                <i class="fab fa-youtube text-danger me-2"></i>
                            @elseif(str_contains($recipe->video_url, 'vimeo'))
                                <i class="fab fa-vimeo text-info me-2"></i>
                            @else
                                <i class="fas fa-video text-primary me-2"></i>
                            @endif
                            Vidéo de la recette
                        </h4>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="ratio ratio-16x9">
                                    @php
                                        $videoUrl = $recipe->video_url;
                                        if (str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be')) {
                                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $videoUrl, $match);
                                            $embedUrl = isset($match[1]) ? "https://www.youtube.com/embed/{$match[1]}" : $videoUrl;
                                        }
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

                <!-- Actions -->
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="{{ route('recipes.print', $recipe->slug) }}"
                       target="_blank"
                       class="btn btn-outline-secondary">
                        <i class="fas fa-print me-2"></i>Imprimer la recette
                    </a>
                    <button onclick="copyToClipboard()" class="btn btn-outline-primary">
                        <i class="fas fa-link me-2"></i>Copier le lien
                    </button>
                </div>

                <!-- Partage social -->
                <div class="mb-5">
                    <h5 class="mb-3">
                        <i class="fas fa-share-alt text-primary me-2"></i>
                        Partager cette recette
                    </h5>
                    <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($recipe->title) }}"
                           target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($recipe->title . ' - ' . url()->current()) }}"
                           target="_blank" rel="noopener noreferrer" class="btn btn-outline-success">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                           target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                            <i class="fab fa-linkedin-in me-2"></i>LinkedIn
                        </a>
                    </div>
                </div>

                <!-- Navigation -->
                @if(isset($previousRecipe) || isset($nextRecipe))
                    <div class="d-flex justify-content-between flex-wrap gap-2 mb-4">
                        @if(isset($previousRecipe))
                            <a href="{{ route('recipes.show', $previousRecipe->slug) }}"
                               class="btn btn-outline-primary d-flex align-items-center">
                                <i class="fas fa-arrow-left me-2"></i>
                                <div class="text-start">
                                    <small class="d-block">Précédent</small>
                                    <strong>{{ Str::limit($previousRecipe->title, 30) }}</strong>
                                </div>
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if(isset($nextRecipe))
                            <a href="{{ route('recipes.show', $nextRecipe->slug) }}"
                               class="btn btn-outline-primary d-flex align-items-center">
                                <div class="text-end">
                                    <small class="d-block">Suivant</small>
                                    <strong>{{ Str::limit($nextRecipe->title, 30) }}</strong>
                                </div>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Infos recette -->
                <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informations
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @if($recipe->category)
                                <li class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">
                                            <i class="fas fa-tag text-primary me-2"></i>Catégorie
                                        </span>
                                        <span class="badge bg-primary">{{ $recipe->category }}</span>
                                    </div>
                                </li>
                            @endif
                            @if($recipe->difficulty)
                                <li class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">
                                            <i class="fas fa-signal text-primary me-2"></i>Difficulté
                                        </span>
                                        <span class="badge bg-{{ $recipe->difficulty == 'Facile' ? 'success' : ($recipe->difficulty == 'Moyen' ? 'warning' : 'danger') }}">
                                            {{ $recipe->difficulty }}
                                        </span>
                                    </div>
                                </li>
                            @endif
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="far fa-clock text-primary me-2"></i>Temps total
                                    </span>
                                    <strong>{{ ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0) }} min</strong>
                                </div>
                            </li>
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="fas fa-utensils text-primary me-2"></i>Ingrédients
                                    </span>
                                    <strong>{{ count($recipe->ingredients) }}</strong>
                                </div>
                            </li>
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="fas fa-list-ol text-primary me-2"></i>Étapes
                                    </span>
                                    <strong>{{ count($recipe->instructions) }}</strong>
                                </div>
                            </li>
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="fas fa-images text-primary me-2"></i>Photos
                                    </span>
                                    <strong>{{ $totalPhotos }}</strong>
                                </div>
                            </li>
                            @if(isset($recipe->views) && $recipe->views > 0)
                                <li class="mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">
                                            <i class="fas fa-eye text-primary me-2"></i>Vues
                                        </span>
                                        <strong>{{ $recipe->views }}</strong>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Recettes similaires -->
                @if(isset($relatedRecipes) && $relatedRecipes->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-layer-group me-2"></i>Recettes similaires
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($relatedRecipes as $related)
                                <div class="p-3 border-bottom hover-bg-light">
                                    <a href="{{ route('recipes.show', $related->slug) }}"
                                       class="text-decoration-none text-dark d-block">
                                        @if($related->image)
                                            <div class="position-relative mb-2">
                                                <img src="{{ asset('storage/' . $related->image) }}"
                                                     class="img-fluid rounded w-100"
                                                     alt="{{ $related->title }}"
                                                     style="height: 150px; object-fit: cover;"
                                                     loading="lazy">
                                                @if($related->difficulty)
                                                    <span class="position-absolute top-0 end-0 m-2 badge bg-{{ $related->difficulty == 'Facile' ? 'success' : ($related->difficulty == 'Moyen' ? 'warning' : 'danger') }}">
                                                        {{ $related->difficulty }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        @if($related->category)
                                            <span class="badge bg-success mb-2">{{ $related->category }}</span>
                                        @endif
                                        <h6 class="mb-2">{{ $related->title }}</h6>
                                        <div class="d-flex justify-content-between text-muted small">
                                            <span>
                                                <i class="far fa-clock me-1"></i>
                                                {{ ($related->prep_time ?? 0) + ($related->cook_time ?? 0) }} min
                                            </span>
                                            @if($related->servings)
                                                <span>
                                                    <i class="fas fa-users me-1"></i>
                                                    {{ $related->servings }} pers.
                                                </span>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer bg-white text-center">
                            <a href="{{ route('recipes.index') }}" class="btn btn-sm btn-outline-success">
                                Voir toutes les recettes <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- CTA -->
                <div class="card border-0 shadow-sm bg-gradient text-white"
                     style="background: linear-gradient(135deg, #198754 0%, #146c43 100%);">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-heart fa-3x mb-3"></i>
                        <h5 class="card-title">Vous avez aimé cette recette ?</h5>
                        <p class="card-text">Partagez-la avec vos proches !</p>
                        <button onclick="copyToClipboard()" class="btn btn-light btn-lg">
                            <i class="fas fa-share-alt me-2"></i>Partager
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Galerie -->
@if($hasGallery || $recipe->image)
<div class="modal fade" id="galleryModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">
                    <i class="fas fa-images me-2"></i>{{ $recipe->title }}
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
                <h3 class="fw-bold mb-3">Découvrez nos autres recettes</h3>
                <p class="lead text-muted mb-lg-0">
                    Explorez notre collection de recettes traditionnelles
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('recipes.index') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-utensils me-2"></i>Toutes les recettes
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
.content p { margin-bottom: 1rem; text-align: justify; }
.content img { max-width: 100%; height: auto; margin: 1rem 0; border-radius: 0.5rem; }

/* Numéros d'étapes */
.step-number {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.3rem;
}

.custom-steps {
    list-style: none;
    padding: 0;
}

/* Ingrédients */
.ingredient-list input:checked + .ingredient-text {
    text-decoration: line-through;
    opacity: 0.5;
}

.cursor-pointer {
    cursor: pointer;
}

/* Étapes terminées */
.step-completed .step-text {
    text-decoration: line-through;
    opacity: 0.6;
}

.step-completed .btn-outline-success {
    background-color: #198754;
    color: white;
}

/* Galerie */
.gallery-item { position: relative; overflow: hidden; border-radius: 0.5rem; transition: transform 0.3s ease; }
.gallery-item:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important; }
.gallery-item img { transition: transform 0.5s ease; }
.gallery-item:hover img { transform: scale(1.1); }
.gallery-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(25,135,84,0.8); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; color: white; z-index: 1; }
.gallery-item:hover .gallery-overlay { opacity: 1; }

/* Thumbnails */
.thumbnail { width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 3px solid transparent; border-radius: 0.25rem; transition: all 0.3s ease; opacity: 0.6; }
.thumbnail:hover { opacity: 1; transform: scale(1.1); }
.thumbnail.active { border-color: #198754; opacity: 1; }

.hover-bg-light { transition: background-color 0.3s ease; }
.hover-bg-light:hover { background-color: #f8f9fa !important; }

@media (min-width: 992px) { .sticky-top { position: sticky !important; } }
</style>
@endpush

@push('scripts')
<script>
// Galerie
const galleryImages = [
    @if($recipe->image)
    { url: '{{ asset('storage/' . $recipe->image) }}', alt: '{{ $recipe->title }}' },
    @endif
    @if($hasGallery)
        @foreach($recipe->gallery as $image)
        { url: '{{ asset('storage/' . $image) }}', alt: '{{ $recipe->title }} - {{ $loop->iteration }}' },
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

// Toggle ingrédient
function toggleIngredient(index) {
    // Marque visuel seulement (ligne barrée)
}

// Toggle étape
function toggleStep(btn) {
    btn.closest('li').classList.toggle('step-completed');
}

// Ajuster portions
function adjustServings(delta) {
    const input = document.getElementById('currentServings');
    const newValue = Math.max(1, Math.min(99, parseInt(input.value) + delta));
    input.value = newValue;
}

// Copier lien
function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Lien copié dans le presse-papier !');
    });
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    if (galleryImages.length > 0) initGallery();
});

// Navigation clavier galerie
document.addEventListener('keydown', (e) => {
    if (document.getElementById('galleryModal').classList.contains('show')) {
        if (e.key === 'ArrowLeft') navigateGallery(-1);
        if (e.key === 'ArrowRight') navigateGallery(1);
        if (e.key === 'Escape') galleryModal.hide();
    }
});
</script>
@endpush
