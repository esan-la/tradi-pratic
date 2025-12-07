@extends('layouts.app')

@section('title', 'Nos Réalisations')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-success text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Nos Réalisations</h1>
                <p class="lead mb-0">Découvrez nos projets en agriculture, élevage et artisanat</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <i class="fas fa-seedling fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Réalisations</li>
        </ol>
    </div>
</nav>

<!-- Filter & Search Section -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <div class="row g-3 align-items-center">
            <!-- Barre de recherche -->
            <div class="col-lg-4">
                <form action="{{ route('realisations') }}" method="GET" class="position-relative">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Rechercher une réalisation..."
                           value="{{ request('search') }}">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <button type="submit" class="btn btn-success position-absolute end-0 top-0">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Filtres par catégorie -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-lg-end justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('realisations', ['search' => request('search')]) }}"
                       class="btn {{ !request('category') ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="fas fa-th me-1"></i> Toutes
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('realisations', ['category' => $category, 'search' => request('search')]) }}"
                           class="btn {{ request('category') == $category ? 'btn-success' : 'btn-outline-success' }}">
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Realisations Grid -->
<section class="py-5">
    <div class="container">
        @if($realisations->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <p class="text-muted">
                        <strong>{{ $realisations->total() }}</strong> réalisation(s) trouvée(s)
                        @if(request('category'))
                            dans la catégorie "<strong>{{ request('category') }}</strong>"
                        @endif
                        @if(request('search'))
                            pour "<strong>{{ request('search') }}</strong>"
                        @endif
                    </p>
                </div>
            </div>

            <div class="row g-4">
                @foreach($realisations as $realisation)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm realisation-card">
                            <!-- Image avec overlay -->
                            <div class="position-relative overflow-hidden" style="height: 250px;">
                                @if($realisation->image)
                                    <img src="{{ asset('storage/' . $realisation->image) }}"
                                         class="card-img-top w-100 h-100"
                                         alt="{{ $realisation->title }}"
                                         style="object-fit: cover; transition: transform 0.3s ease;"
                                         loading="lazy">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="fas fa-image fa-4x text-muted"></i>
                                    </div>
                                @endif

                                <!-- Badge catégorie -->
                                <span class="position-absolute top-0 end-0 m-3 badge bg-success">
                                    {{ $realisation->category }}
                                </span>

                                <!-- Badge vedette si applicable -->
                                @if($realisation->is_featured)
                                    <span class="position-absolute top-0 start-0 m-3 badge bg-warning text-dark">
                                        <i class="fas fa-star me-1"></i>Vedette
                                    </span>
                                @endif

                                <!-- Badge galerie si présente -->
                                @if($realisation->hasGallery())
                                    <span class="position-absolute bottom-0 start-0 m-3 badge bg-dark bg-opacity-75">
                                        <i class="fas fa-images me-1"></i>{{ $realisation->gallery_count + 1 }} photos
                                    </span>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-3">
                                    <a href="{{ route('realisations.show', $realisation->slug) }}"
                                       class="text-decoration-none text-dark stretched-link">
                                        {{ $realisation->title }}
                                    </a>
                                </h5>

                                <p class="card-text text-muted flex-grow-1" style="line-height: 1.6;">
                                    {{ Str::limit($realisation->description, 120) }}
                                </p>

                                <!-- Métadonnées -->
                                <div class="d-flex justify-content-between align-items-center text-muted small mt-3 pt-3 border-top">
                                    <div>
                                        <i class="far fa-calendar me-1"></i>
                                        {{ $realisation->created_at->format('d/m/Y') }}
                                    </div>
                                    @if($realisation->video_url)
                                        <div class="text-danger">
                                            <i class="fab fa-youtube me-1"></i>Vidéo
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer bg-white border-0 pt-0">
                                <a href="{{ route('realisations.show', $realisation->slug) }}"
                                   class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-arrow-right me-1"></i> Découvrir le projet
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    {{ $realisations->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                        <h3 class="text-muted mb-3">Aucune réalisation trouvée</h3>
                        <p class="text-muted mb-4">
                            @if(request('search'))
                                Aucun résultat pour "<strong>{{ request('search') }}</strong>"
                                @if(request('category'))
                                    dans la catégorie "<strong>{{ request('category') }}</strong>"
                                @endif
                            @elseif(request('category'))
                                Aucune réalisation dans la catégorie "<strong>{{ request('category') }}</strong>"
                            @else
                                Revenez plus tard pour découvrir nos projets
                            @endif
                        </p>

                        @if(request('search') || request('category'))
                            <div class="d-flex justify-content-center gap-2">
                                @if(request('search'))
                                    <a href="{{ route('realisations.index', ['category' => request('category')]) }}"
                                       class="btn btn-outline-success">
                                        <i class="fas fa-times me-2"></i> Effacer la recherche
                                    </a>
                                @endif

                                @if(request('category'))
                                    <a href="{{ route('realisations.index', ['search' => request('search')]) }}"
                                       class="btn btn-outline-success">
                                        <i class="fas fa-times me-2"></i> Effacer le filtre
                                    </a>
                                @endif

                                <a href="{{ route('realisations.index') }}" class="btn btn-success">
                                    <i class="fas fa-redo me-2"></i> Voir toutes les réalisations
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Intéressé par nos projets ?</h3>
                <p class="lead text-muted mb-lg-0">
                    Contactez-nous pour en savoir plus sur nos réalisations et collaborations
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('contact') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-envelope me-2"></i> Nous contacter
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .realisation-card {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .realisation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }

    .realisation-card:hover img {
        transform: scale(1.05);
    }

    .realisation-card .stretched-link::after {
        z-index: 0;
    }

    .realisation-card .btn,
    .realisation-card .badge {
        position: relative;
        z-index: 1;
    }

    /* Style pour la barre de recherche */
    .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }

    /* Animation pour les cartes */
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

    .realisation-card {
        animation: fadeIn 0.5s ease-out;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit du formulaire de recherche si vide
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.querySelector('form[action*="realisations"]');
        const searchInput = searchForm?.querySelector('input[name="search"]');

        if (searchInput) {
            // Permettre de soumettre avec Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchForm.submit();
                }
            });
        }
    });
</script>
@endpush

