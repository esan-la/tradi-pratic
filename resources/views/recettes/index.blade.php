@extends('layouts.app')

@section('title', 'Recettes Traditionnelles - Adja Amsetou')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-success text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Recettes Traditionnelles</h1>
                <p class="lead mb-0">Découvrez les saveurs authentiques du Burkina Faso et les secrets de la cuisine traditionnelle</p>
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

<!-- Filters and Search -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <form action="{{ route('recipes') }}" method="GET" class="input-group">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Rechercher une recette..."
                           value="{{ request('search') }}">
                    <button class="btn btn-success" type="submit">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </form>
            </div>

            <!-- Filtres par catégorie -->
            <div class="col-md-6">
                <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                    <a href="{{ route('recipes', ['search' => request('search')]) }}"
                       class="btn {{ !request('category') ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="fas fa-th me-1"></i> Toutes
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('recipes', ['category' => $category, 'search' => request('search')]) }}"
                           class="btn {{ request('category') == $category ? 'btn-success' : 'btn-outline-success' }}">
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recipes Grid -->
<section class="py-5">
    <div class="container">
        @if($recipes->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <p class="text-muted">
                        <strong>{{ $recipes->total() }}</strong> recette(s) trouvée(s)
                        @if(request('search'))
                            pour "<strong>{{ request('search') }}</strong>"
                        @endif
                        @if(request('category'))
                            dans la catégorie "<strong>{{ ucfirst(request('category')) }}</strong>"
                        @endif
                    </p>
                </div>
            </div>

            <div class="row g-4">
                @foreach($recipes as $recipe)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm recipe-card">
                            @if($recipe->image)
                                <div class="position-relative overflow-hidden" style="height: 250px;">
                                    <img src="{{ asset('storage/' . $recipe->image) }}"
                                         class="card-img-top w-100 h-100"
                                         alt="{{ $recipe->title }}"
                                         style="object-fit: cover;">
                                    @if($recipe->category)
                                        <span class="position-absolute top-0 end-0 m-3 badge bg-success">
                                            {{ ucfirst($recipe->category) }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <i class="fas fa-utensils fa-4x text-muted"></i>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="{{ route('recipes.show', $recipe->slug) }}"
                                       class="text-decoration-none text-dark stretched-link">
                                        {{ $recipe->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted flex-grow-1">
                                    {{ Str::limit($recipe->description, 100) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center text-muted small mt-3">
                                    @if($recipe->prep_time || $recipe->cook_time)
                                        <span>
                                            <i class="far fa-clock me-1"></i>
                                            {{ ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0) }} min
                                        </span>
                                    @endif

                                    @if($recipe->servings)
                                        <span>
                                            <i class="fas fa-users me-1"></i>
                                            {{ $recipe->servings }} pers.
                                        </span>
                                    @endif

                                    <span>
                                        <i class="far fa-eye me-1"></i>
                                        {{ $recipe->views_count ?? 0 }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-0 pt-0">
                                <a href="{{ route('recipes.show', $recipe->slug) }}"
                                   class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-arrow-right me-1"></i> Voir la recette
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    {{ $recipes->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-utensils fa-4x text-muted mb-4"></i>
                        <h3 class="text-muted">Aucune recette trouvée</h3>
                        <p class="text-muted mb-4">
                            @if(request('search') || request('category'))
                                Essayez de modifier vos critères de recherche
                            @else
                                Les recettes seront bientôt disponibles
                            @endif
                        </p>
                        @if(request('search') || request('category'))
                            <a href="{{ route('recipes') }}" class="btn btn-success">
                                <i class="fas fa-redo me-2"></i> Réinitialiser les filtres
                            </a>
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
                <h3 class="fw-bold mb-3">Besoin de conseils personnalisés ?</h3>
                <p class="lead text-muted mb-lg-0">
                    Consultez Adja Amsetou pour des recommandations adaptées à vos besoins
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('consultations') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-calendar-check me-2"></i> Prendre rendez-vous
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
}

.recipe-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.recipe-card .stretched-link::after {
    z-index: 0;
}

.recipe-card .btn {
    position: relative;
    z-index: 1;
}
</style>
@endpush
