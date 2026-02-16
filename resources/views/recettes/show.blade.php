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
                    <div class="position-relative mb-4 recipe-image-container">
                        <img src="{{ asset('storage/' . $recipe->image) }}"
                             class="img-fluid rounded shadow-sm w-100"
                             alt="{{ $recipe->title }}"
                             style="max-height: 500px; object-fit: cover; cursor: zoom-in;"
                             onclick="openImageModal()"
                             onerror="this.src='{{ asset('images/recipe-placeholder.jpg') }}'">

                        <!-- Badge difficulté (si disponible) -->
                        <span class="position-absolute top-0 end-0 m-3 badge bg-primary">
                            <i class="fas fa-utensils me-1"></i>Recette Traditionnelle
                        </span>
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
                        <div class="card border-0 bg-light h-100 info-card">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-2x text-primary mb-3"></i>
                                <h6 class="mb-2">Préparation</h6>
                                <p class="h5 mb-0 text-primary">{{ $recipe->prep_time ?? 0 }} min</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100 info-card">
                            <div class="card-body text-center">
                                <i class="fas fa-fire fa-2x text-danger mb-3"></i>
                                <h6 class="mb-2">Cuisson</h6>
                                <p class="h5 mb-0 text-danger">{{ $recipe->cook_time ?? 0 }} min</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100 info-card">
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
                                <ul class="list-unstyled mb-0 ingredients-list">
                                    @foreach($recipe->ingredients as $ingredient)
                                        <li class="mb-3 d-flex align-items-start ingredient-item">
                                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                            <span>{{ $ingredient }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-footer bg-light">
                                <button class="btn btn-sm btn-outline-primary" onclick="printIngredients()">
                                    <i class="fas fa-print me-2"></i>Imprimer la liste
                                </button>
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
                                    <div class="d-flex mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }} instruction-step">
                                        <div class="me-3">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center step-number"
                                                 style="width: 45px; height: 45px; min-width: 45px;">
                                                <strong>{{ $index + 1 }}</strong>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-0" style="line-height: 1.8; font-size: 1.05rem;">
                                                {{ $instruction }}
                                            </p>
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

                <!-- Conseils -->
                <div class="mb-5">
                    <div class="card border-0 shadow-sm bg-light">
                        <div class="card-body">
                            <h5 class="mb-3">
                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                Conseils du chef
                            </h5>
                            <ul class="mb-0">
                                <li class="mb-2">Préparez tous vos ingrédients avant de commencer la cuisson</li>
                                <li class="mb-2">Respectez les temps de cuisson pour un résultat optimal</li>
                                <li class="mb-0">N'hésitez pas à ajuster les épices selon vos goûts</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Boutons de partage -->
                <div class="mb-5">
                    <h5 class="mb-3">
                        <i class="fas fa-share-alt text-primary me-2"></i>
                        Partager cette recette
                    </h5>
                    <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded">
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
                        <button onclick="printRecipe()" class="btn btn-outline-dark">
                            <i class="fas fa-print me-2"></i>Imprimer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Informations -->
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
                                    <strong>{{ $recipe->views_count ?? 0 }}</strong>
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
                                            <div class="position-relative mb-2 related-recipe-image">
                                                <img src="{{ asset('storage/' . $related->image) }}"
                                                     class="img-fluid rounded w-100"
                                                     alt="{{ $related->title }}"
                                                     style="height: 150px; object-fit: cover;"
                                                     onerror="this.src='{{ asset('images/recipe-placeholder.jpg') }}'"
                                                     loading="lazy">
                                            </div>
                                        @endif
                                        <h6 class="mb-2">{{ $related->title }}</h6>
                                        <p class="text-muted small mb-2">
                                            {{ Str::limit($related->description, 60) }}
                                        </p>
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

<!-- Modal Image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">{{ $recipe->title }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                @if($recipe->image)
                    <img src="{{ asset('storage/' . $recipe->image) }}"
                         class="img-fluid w-100"
                         alt="{{ $recipe->title }}"
                         style="max-height: 85vh; object-fit: contain;">
                @endif
            </div>
        </div>
    </div>
</div>

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
    /* Amélioration qualité images */
    img {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }

    .recipe-image-container img,
    .related-recipe-image img {
        image-rendering: auto;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        transition: transform 0.3s ease;
    }

    .recipe-image-container:hover img {
        transform: scale(1.02);
    }

    .related-recipe-image:hover img {
        transform: scale(1.05);
    }

    /* Info cards */
    .info-card {
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .info-card i {
        transition: transform 0.3s ease;
    }

    .info-card:hover i {
        transform: scale(1.2);
    }

    /* Ingrédients */
    .ingredient-item {
        transition: all 0.3s ease;
        padding: 8px;
        border-radius: 5px;
    }

    .ingredient-item:hover {
        background-color: #f8f9fa;
        padding-left: 15px;
    }

    /* Instructions */
    .instruction-step {
        transition: all 0.3s ease;
    }

    .instruction-step:hover {
        background-color: #f8f9fa;
        padding: 15px !important;
        border-radius: 8px;
    }

    .step-number {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        transition: all 0.3s ease;
    }

    .instruction-step:hover .step-number {
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(13, 110, 253, 0.4);
    }

    /* Hover effects */
    .hover-bg-light {
        transition: background-color 0.3s ease;
    }

    .hover-bg-light:hover {
        background-color: #f8f9fa !important;
    }

    /* Animation */
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

    /* Print styles */
    @media print {
        .btn, .card-header, nav, footer, .share-buttons {
            display: none !important;
        }

        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }

        body {
            font-size: 12pt;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Modal image
    function openImageModal() {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }

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

    // Imprimer la recette
    function printRecipe() {
        window.print();
    }

    // Imprimer juste les ingrédients
    function printIngredients() {
        const ingredients = document.querySelector('.ingredients-list').innerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Liste des ingrédients</title>');
        printWindow.document.write('<style>body{font-family:Arial,sans-serif;padding:20px;} li{margin-bottom:10px;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h1>{{ $recipe->title }}</h1>');
        printWindow.document.write('<h2>Ingrédients</h2>');
        printWindow.document.write('<ul>' + ingredients + '</ul>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }

    // Incrémenter le compteur de vues (optionnel)
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des étapes au scroll
        const steps = document.querySelectorAll('.instruction-step');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.5s ease-out';
                }
            });
        }, { threshold: 0.1 });

        steps.forEach(step => observer.observe(step));
    });
</script>
@endpush
