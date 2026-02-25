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
                @if(isset($stats))
                    <p class="mt-3 mb-0">
                        <span class="badge bg-white text-success fs-6">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ $stats['total'] }} projets réalisés
                        </span>
                    </p>
                @endif
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

<!-- Filtres -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <form action="{{ route('realisations.index') }}" method="GET" id="filterForm">
            <div class="row g-3 align-items-end">
                <!-- Recherche -->
                <div class="col-lg-4 col-md-6">
                    <label class="form-label small text-muted mb-1">Recherche</label>
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control pe-5"
                               placeholder="Rechercher..." value="{{ request('search') }}">
                        @if(request('search'))
                            <button type="button"
                                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted"
                                    onclick="clearSearch()">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Tri -->
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small text-muted mb-1">Trier par</label>
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Plus récents</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Titre (A-Z)</option>
                    </select>
                </div>

                <!-- Par page -->
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small text-muted mb-1">Afficher</label>
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="fas fa-search me-1"></i> Rechercher
                        </button>
                        @if(request()->hasAny(['search', 'category', 'sort', 'per_page']))
                            <a href="{{ route('realisations.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Catégories -->
            <div class="row mt-3">
                <div class="col-12">
                    <label class="form-label small text-muted mb-2">Catégories</label>
                    <div class="d-flex gap-2 flex-wrap">
                        <input type="radio" class="btn-check" name="category" id="cat-all" value=""
                               {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="btn btn-sm btn-outline-success" for="cat-all">
                            <i class="fas fa-th me-1"></i> Toutes
                            @if(isset($stats))
                                <span class="badge bg-success">{{ $stats['total'] }}</span>
                            @endif
                        </label>

                        @foreach($categories as $key => $category)
                            <input type="radio" class="btn-check" name="category" id="cat-{{ $key }}"
                                   value="{{ $category }}" {{ request('category') == $category ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <label class="btn btn-sm btn-outline-success" for="cat-{{ $key }}">
                                {{ $category }}
                                @if(isset($stats['by_category'][$category]))
                                    <span class="badge bg-success">{{ $stats['by_category'][$category] }}</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Grid -->
<section class="py-5">
    <div class="container">
        @if($realisations->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-muted mb-0">
                            <strong>{{ $realisations->total() }}</strong> réalisation(s)
                            @if(request('category'))
                                dans "<strong>{{ request('category') }}</strong>"
                            @endif
                        </p>
                        <p class="text-muted mb-0 small">
                            Page {{ $realisations->currentPage() }} sur {{ $realisations->lastPage() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @foreach($realisations as $realisation)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm realisation-card">
                            <!-- Image -->
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

                                <!-- Badge vedette -->
                                @if($realisation->is_featured ?? false)
                                    <span class="position-absolute top-0 start-0 m-3 badge bg-warning text-dark">
                                        <i class="fas fa-star me-1"></i>Vedette
                                    </span>
                                @endif

                                <!-- Badge galerie -->
                                @php
                                    $hasGallery = isset($realisation->gallery) && is_array($realisation->gallery) && count($realisation->gallery) > 0;
                                    $totalPhotos = $hasGallery ? count($realisation->gallery) + 1 : 1;
                                @endphp
                                @if($hasGallery)
                                    <span class="position-absolute bottom-0 start-0 m-3 badge bg-dark bg-opacity-75">
                                        <i class="fas fa-images me-1"></i>{{ $totalPhotos }} photos
                                    </span>
                                @endif

                                <!-- Badge vidéo -->
                                @if($realisation->video_url)
                                    <span class="position-absolute bottom-0 end-0 m-3 badge bg-danger">
                                        <i class="fab fa-youtube me-1"></i>Vidéo
                                    </span>
                                @endif

                                <!-- Badge vues -->
                                @if(isset($realisation->views) && $realisation->views > 0)
                                    <span class="position-absolute top-0 start-0 mt-5 ms-3 badge bg-dark bg-opacity-75">
                                        <i class="fas fa-eye me-1"></i>{{ $realisation->views }}
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
                                    {{ Str::limit($realisation->short_description ?? strip_tags($realisation->description), 120) }}
                                </p>

                                <!-- Métadonnées -->
                                <div class="d-flex justify-content-between align-items-center text-muted small mt-3 pt-3 border-top">
                                    <div>
                                        <i class="far fa-calendar me-1"></i>
                                        {{ $realisation->created_at->format('d/m/Y') }}
                                    </div>
                                    <div>
                                        <i class="fas fa-images me-1"></i>{{ $totalPhotos }}
                                        @if($realisation->video_url)
                                            <i class="fab fa-youtube ms-2 text-danger"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-0 pt-0">
                                <a href="{{ route('realisations.show', $realisation->slug) }}"
                                   class="btn btn-outline-success btn-sm w-100 position-relative">
                                    <i class="fas fa-arrow-right me-1"></i> Découvrir le projet
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row mt-5">
                <div class="col-12">
                    {{ $realisations->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                <h3 class="text-muted mb-3">Aucune réalisation trouvée</h3>
                <p class="text-muted mb-4">
                    @if(request('search'))
                        Aucun résultat pour "<strong>{{ request('search') }}</strong>"
                    @else
                        Revenez plus tard pour découvrir nos projets
                    @endif
                </p>
                @if(request()->hasAny(['search', 'category']))
                    <a href="{{ route('realisations.index') }}" class="btn btn-success">
                        <i class="fas fa-redo me-2"></i> Tout afficher
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<!-- CTA -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Intéressé par nos projets ?</h3>
                <p class="lead text-muted mb-lg-0">
                    Contactez-nous pour en savoir plus sur nos réalisations
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

.btn-check:checked + .btn-outline-success {
    background-color: #198754;
    border-color: #198754;
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
function clearSearch() {
    document.querySelector('input[name="search"]').value = '';
    document.getElementById('filterForm').submit();
}
</script>
@endpush
