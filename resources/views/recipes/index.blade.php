@extends('layouts.app')

@section('title', 'Nos Recettes')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-success text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Nos Recettes</h1>
                <p class="lead mb-0">Découvrez nos délicieuses recettes traditionnelles</p>
                @if(isset($stats))
                    <p class="mt-3 mb-0">
                        <span class="badge bg-white text-success fs-6">
                            <i class="fas fa-utensils me-1"></i>
                            {{ $stats['total'] }} recettes disponibles
                        </span>
                    </p>
                @endif
            </div>
            <div class="col-lg-4 text-lg-end">
                <i class="fas fa-utensils fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Recettes</li>
        </ol>
    </div>
</nav>

<!-- Filtres -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <form action="{{ route('recipes.index') }}" method="GET" id="filterForm">
            <div class="row g-3 align-items-end">
                <!-- Recherche -->
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small text-muted mb-1">Recherche</label>
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control pe-5"
                               placeholder="Chercher une recette..." value="{{ request('search') }}">
                        @if(request('search'))
                            <button type="button"
                                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted"
                                    onclick="clearSearch()">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Catégorie -->
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small text-muted mb-1">Catégorie</label>
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes</option>
                        @foreach($categories as $key => $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Difficulté -->
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small text-muted mb-1">Difficulté</label>
                    <select name="difficulty" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes</option>
                        @foreach($difficulties as $key => $difficulty)
                            <option value="{{ $difficulty }}" {{ request('difficulty') == $difficulty ? 'selected' : '' }}>
                                {{ $difficulty }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tri -->
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small text-muted mb-1">Trier par</label>
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Plus récentes</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Titre (A-Z)</option>
                        <option value="time" {{ request('sort') == 'time' ? 'selected' : '' }}>Plus rapides</option>
                        <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Plus vues</option>
                    </select>
                </div>

                <!-- Par page -->
                <div class="col-lg-1 col-md-6">
                    <label class="form-label small text-muted mb-1">Afficher</label>
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="col-lg-2 col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-1"></i> Rechercher
                        </button>
                        @if(request()->hasAny(['search', 'category', 'difficulty', 'sort', 'per_page']))
                            <a href="{{ route('recipes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Grid -->
<section class="py-5">
    <div class="container">
        @if($recipes->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-muted mb-0">
                            <strong>{{ $recipes->total() }}</strong> recette(s)
                            @if(request('category'))
                                dans "<strong>{{ request('category') }}</strong>"
                            @endif
                            @if(request('difficulty'))
                                · Difficulté : <strong>{{ request('difficulty') }}</strong>
                            @endif
                        </p>
                        <p class="text-muted mb-0 small">
                            Page {{ $recipes->currentPage() }} sur {{ $recipes->lastPage() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @foreach($recipes as $recipe)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm recipe-card">
                            <!-- Image -->
                            <div class="position-relative overflow-hidden" style="height: 250px;">
                                @if($recipe->image)
                                    <img src="{{ asset('storage/' . $recipe->image) }}"
                                         class="card-img-top w-100 h-100"
                                         alt="{{ $recipe->title }}"
                                         style="object-fit: cover; transition: transform 0.3s ease;"
                                         loading="lazy">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="fas fa-utensils fa-4x text-muted"></i>
                                    </div>
                                @endif

                                <!-- Badge catégorie -->
                                @if($recipe->category)
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-success text-white">
                                        {{ $recipe->category }}
                                    </span>
                                @endif

                                <!-- Badge vedette -->
                                @if($recipe->is_featured ?? false)
                                    <span class="position-absolute top-0 start-0 m-3 badge bg-warning text-dark">
                                        <i class="fas fa-star me-1"></i>Vedette
                                    </span>
                                @endif

                                <!-- Temps total -->
                                @php
                                    $totalTime = ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0);
                                @endphp
                                @if($totalTime > 0)
                                    <span class="position-absolute bottom-0 start-0 m-3 badge bg-dark bg-opacity-75">
                                        <i class="far fa-clock me-1"></i>{{ $totalTime }} min
                                    </span>
                                @endif

                                <!-- Difficulté -->
                                @if($recipe->difficulty)
                                    <span class="position-absolute bottom-0 end-0 m-3 badge bg-{{ $recipe->difficulty == 'Facile' ? 'success' : ($recipe->difficulty == 'Moyen' ? 'warning' : 'danger') }}">
                                        {{ $recipe->difficulty }}
                                    </span>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-3">
                                    <a href="{{ route('recipes.show', $recipe->slug) }}"
                                       class="text-decoration-none text-dark stretched-link">
                                        {{ $recipe->title }}
                                    </a>
                                </h5>

                                <p class="card-text text-muted flex-grow-1" style="line-height: 1.6;">
                                    {{ Str::limit($recipe->short_description ?? strip_tags($recipe->description), 100) }}
                                </p>

                                <!-- Métadonnées -->
                                <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                                    @if($totalTime > 0)
                                        <span class="badge bg-info text-dark">
                                            <i class="far fa-clock"></i> {{ $totalTime }} min
                                        </span>
                                    @endif
                                    @if($recipe->servings)
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-users"></i> {{ $recipe->servings }} pers.
                                        </span>
                                    @endif
                                    @if($recipe->difficulty)
                                        <span class="badge bg-{{ $recipe->difficulty == 'Facile' ? 'success' : ($recipe->difficulty == 'Moyen' ? 'warning' : 'danger') }}">
                                            {{ $recipe->difficulty }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer bg-white border-0 pt-0">
                                <a href="{{ route('recipes.show', $recipe->slug) }}"
                                   class="btn btn-outline-success btn-sm w-100 position-relative">
                                    <i class="fas fa-arrow-right me-1"></i> Voir la recette
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row mt-5">
                <div class="col-12">
                    {{ $recipes->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="fas fa-utensils fa-4x text-muted mb-4"></i>
                <h3 class="text-muted mb-3">Aucune recette trouvée</h3>
                <p class="text-muted mb-4">
                    @if(request('search'))
                        Aucun résultat pour "<strong>{{ request('search') }}</strong>"
                    @else
                        Revenez plus tard pour découvrir nos recettes
                    @endif
                </p>
                @if(request()->hasAny(['search', 'category', 'difficulty']))
                    <a href="{{ route('recipes.index') }}" class="btn btn-primary">
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
                <h3 class="fw-bold mb-3">Vous avez une recette à partager ?</h3>
                <p class="lead text-muted mb-lg-0">
                    Contactez-nous pour nous proposer vos recettes traditionnelles
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-envelope me-2"></i> Nous contacter
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.recipe-card {
    transition: all 0.3s ease;
    overflow: hidden;
}

.recipe-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.recipe-card:hover img {
    transform: scale(1.05);
}

.recipe-card .stretched-link::after {
    z-index: 0;
}

.recipe-card .btn,
.recipe-card .badge {
    position: relative;
    z-index: 1;
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
