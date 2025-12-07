@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recipes') }}">Recettes</a></li>
            <li class="breadcrumb-item active">{{ $recipe->title }}</li>
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
                @if($recipe->image)
                    <div class="position-relative mb-4">
                        <img src="{{ asset('storage/' . $recipe->image) }}"
                             class="img-fluid rounded shadow-sm w-100"
                             alt="{{ $recipe->title }}"
                             style="max-height: 500px; object-fit: cover;">
                    </div>
                @endif

                <!-- Titre et description -->
                <div class="mb-4">
                    <h1 class="display-5 fw-bold mb-3">{{ $recipe->title }}</h1>
                    <p class="lead text-muted" style="line-height: 1.8;">
                        {{ $recipe->description }}
                    </p>
                </div>

                <!-- Informations rapides -->
                <div class="row g-3 mb-5">
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-2x text-primary mb-3"></i>
                                <h6 class="mb-2">Préparation</h6>
                                <p class="h5 mb-0 text-primary">{{ $recipe->prep_time ?? 0 }} min</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-fire fa-2x text-danger mb-3"></i>
                                <h6 class="mb-2">Cuisson</h6>
                                <p class="h5 mb-0 text-danger">{{ $recipe->cook_time ?? 0 }} min</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x text-success mb-3"></i>
                                <h6 class="mb-2">Portions</h6>
                                <p class="h5 mb-0 text-success">{{ $recipe->servings ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Temps total -->
                <div class="alert alert-info d-flex align-items-center mb-5">
                    <i class="fas fa-hourglass-half fa-2x me-3"></i>
                    <div>
                        <strong>Temps total:</strong> {{ $recipe->total_time_formatted }}
                    </div>
                </div>

                <!-- Ingrédients -->
                <div class="mb-5">
                    <h3 class="mb-4">
                        <i class="fas fa-shopping-basket text-primary me-2"></i>
                        Ingrédients
                        <small class="text-muted">({{ $recipe->ingredients_count }})</small>
                    </h3>

                    @if($recipe->ingredients && count($recipe->ingredients) > 0)
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    @foreach($recipe->ingredients as $ingredient)
                                        <li class="mb-3 d-flex align-items-start">
                                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                            <span>{{ $ingredient }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Aucun ingrédient spécifié pour cette recette.
                        </div>
                    @endif
                </div>

                <!-- Instructions -->
                <div class="mb-5">
                    <h3 class="mb-4">
                        <i class="fas fa-list-ol text-primary me-2"></i>
                        Préparation
                        <small class="text-muted">({{ $recipe->instructions_count }} étapes)</small>
                    </h3>

                    @if($recipe->instructions && count($recipe->instructions) > 0)
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                @foreach($recipe->instructions as $index => $instruction)
                                    <div class="d-flex mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="me-3">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px; min-width: 40px;">
                                                <strong>{{ $index + 1 }}</strong>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-0" style="line-height: 1.8;">{{ $instruction }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Aucune instruction disponible pour cette recette.
                        </div>
                    @endif
                </div>

                <!-- Vidéo YouTube -->
                @if($recipe->hasVideo())
                    <div class="mb-5">
                        <h3 class="mb-4">
                            <i class="fab fa-youtube text-danger me-2"></i>
                            Vidéo de la recette
                        </h3>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="ratio ratio-16x9">
                                    <iframe
                                        src="{{ $recipe->youtube_embed_url }}"
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
                        Partager cette recette
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank"
                           class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($recipe->title) }}"
                           target="_blank"
                           class="btn btn-outline-info">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($recipe->title . ' - ' . url()->current()) }}"
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
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Informations nutritionnelles (optionnel) -->
                <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informations
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-clock text-primary me-2"></i>Préparation</span>
                                    <strong>{{ $recipe->prep_time ?? 0 }} min</strong>
                                </div>
                            </li>
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-fire text-danger me-2"></i>Cuisson</span>
                                    <strong>{{ $recipe->cook_time ?? 0 }} min</strong>
                                </div>
                            </li>
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-hourglass-half text-warning me-2"></i>Total</span>
                                    <strong>{{ $recipe->total_time_formatted }}</strong>
                                </div>
                            </li>
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users text-success me-2"></i>Portions</span>
                                    <strong>{{ $recipe->servings ?? 'N/A' }}</strong>
                                </div>
                            </li>
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-shopping-basket text-info me-2"></i>Ingrédients</span>
                                    <strong>{{ $recipe->ingredients_count }}</strong>
                                </div>
                            </li>
                            <li class="mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-eye text-secondary me-2"></i>Vues</span>
                                    <strong>{{ $recipe->views_count }}</strong>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Recettes similaires -->
                @if($relatedRecipes->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-utensils me-2"></i>Autres recettes
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($relatedRecipes as $related)
                                <div class="p-3 border-bottom hover-bg-light">
                                    <a href="{{ route('recipes.show', $related->slug) }}"
                                       class="text-decoration-none text-dark d-block">
                                        @if($related->image)
                                            <img src="{{ asset('storage/' . $related->image) }}"
                                                 class="img-fluid rounded mb-2 w-100"
                                                 alt="{{ $related->title }}"
                                                 style="height: 150px; object-fit: cover;"
                                                 loading="lazy">
                                        @endif
                                        <h6 class="mb-2">{{ $related->title }}</h6>
                                        <div class="d-flex justify-content-between text-muted small">
                                            <span>
                                                <i class="far fa-clock me-1"></i>
                                                {{ $related->total_time_formatted }}
                                            </span>
                                            <span>
                                                <i class="fas fa-users me-1"></i>
                                                {{ $related->servings ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer bg-white text-center">
                            <a href="{{ route('recipes') }}" class="btn btn-sm btn-outline-success">
                                Voir toutes les recettes <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Vous avez aimé cette recette ?</h3>
                <p class="lead text-muted mb-lg-0">
                    Découvrez plus de recettes traditionnelles et conseils culinaires
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('recipes') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-utensils me-2"></i>Toutes les recettes
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .hover-bg-light {
        transition: background-color 0.3s ease;
    }

    .hover-bg-light:hover {
        background-color: #f8f9fa !important;
    }

    /* Animation pour les étapes */
    @keyframes fadeInUp {
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
        animation: fadeInUp 0.5s ease-out;
    }

    /* Style pour les listes d'ingrédients */
    .list-unstyled li {
        transition: all 0.3s ease;
    }

    .list-unstyled li:hover {
        padding-left: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    /* Sticky sidebar */
    @media (min-width: 992px) {
        .sticky-top {
            position: sticky;
            top: 20px;
            z-index: 1020;
        }
    }

    /* Responsive iframe */
    .ratio iframe {
        border: none;
    }

    /* Style pour les numéros d'étapes */
    .bg-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }
</style>
@endpush

@push('scripts')
<script>
    // Fonction pour copier le lien
    function copyToClipboard() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(function() {
            // Afficher un message de succès
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

    // Incrémenter le compteur de vues (optionnel)
    document.addEventListener('DOMContentLoaded', function() {
        // Vous pouvez ajouter une requête AJAX pour incrémenter les vues
        // fetch('/recipes/{{ $recipe->slug }}/increment-views', { method: 'POST' });
    });
</script>
@endpush
