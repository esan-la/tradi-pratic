@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('recettes.index') }}">Recettes</a></li>
            <li class="breadcrumb-item active">{{ $recipe->title }}</li>
        </ol>
    </div>
</nav>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Image principale -->
                @if($recipe->image)
                    <img src="{{ asset('storage/' . $recipe->image) }}"
                         class="img-fluid rounded shadow-sm mb-4 w-100"
                         alt="{{ $recipe->title }}"
                         style="max-height: 400px; object-fit: cover;">
                @endif

                <!-- Titre et métadonnées -->
                <div class="mb-4">
                    <span class="badge bg-success mb-2">{{ ucfirst($recipe->category) }}</span>
                    <h1 class="display-5 fw-bold">{{ $recipe->title }}</h1>

                    <div class="d-flex gap-3 text-muted">
                        <span><i class="far fa-eye me-1"></i> {{ $recipe->views_count ?? 0 }} vues</span>
                        <span><i class="far fa-clock me-1"></i> {{ $recipe->preparation_time ?? 'N/A' }}</span>
                        <span><i class="far fa-calendar me-1"></i> {{ $recipe->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h5 mb-3"><i class="fas fa-info-circle text-success me-2"></i>Description</h3>
                        <p class="text-justify">{{ $recipe->description }}</p>
                    </div>
                </div>

                <!-- Ingrédients -->
                @if($recipe->ingredients)
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="h5 mb-3"><i class="fas fa-list-ul text-success me-2"></i>Ingrédients</h3>
                            <ul class="list-group list-group-flush">
                                @foreach(explode("\n", $recipe->ingredients) as $ingredient)
                                    @if(trim($ingredient))
                                        <li class="list-group-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            {{ $ingredient }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Instructions -->
                @if($recipe->instructions)
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="h5 mb-3"><i class="fas fa-tasks text-success me-2"></i>Préparation</h3>
                            <ol class="list-group list-group-numbered">
                                @foreach(explode("\n", $recipe->instructions) as $index => $instruction)
                                    @if(trim($instruction))
                                        <li class="list-group-item">{{ $instruction }}</li>
                                    @endif
                                @endforeach
                            </ol>
                        </div>
                    </div>
                @endif

                <!-- Précautions -->
                @if($recipe->precautions)
                    <div class="alert alert-warning shadow-sm">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Précautions</h5>
                        <p class="mb-0">{{ $recipe->precautions }}</p>
                    </div>
                @endif

                <!-- Partage -->
                <div class="d-flex gap-2 mb-4">
                    <span class="fw-bold me-2">Partager:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                       target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $recipe->title }}"
                       target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://wa.me/?text={{ $recipe->title }} {{ url()->current() }}"
                       target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Recettes similaires -->
                @if($relatedRecipes->count() > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Recettes similaires</h5>
                        </div>
                        <div class="card-body">
                            @foreach($relatedRecipes as $related)
                                <div class="mb-3 pb-3 border-bottom">
                                    @if($related->image)
                                        <img src="{{ asset('storage/' . $related->image) }}"
                                             class="img-fluid rounded mb-2"
                                             alt="{{ $related->title }}"
                                             style="height: 120px; width: 100%; object-fit: cover;">
                                    @endif
                                    <h6>
                                        <a href="{{ route('recettes.show', $related->slug) }}"
                                           class="text-decoration-none text-dark">
                                            {{ $related->title }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="far fa-eye"></i> {{ $related->views_count ?? 0 }} vues
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- CTA -->
                <div class="card shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Besoin de conseils ?</h5>
                        <p class="card-text">Consultez Adja Amsetou pour un traitement personnalisé</p>
                        <a href="{{ route('appointments.create') }}" class="btn btn-light btn-lg">
                            Prendre RDV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
