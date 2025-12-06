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
            <div class="col-lg-8">
                <!-- Image principale -->
                @if($realisation->image)
                    <img src="{{ asset('storage/' . $realisation->image) }}"
                         class="img-fluid rounded shadow-sm mb-4 w-100"
                         alt="{{ $realisation->title }}"
                         style="max-height: 500px; object-fit: cover;">
                @endif

                <!-- Titre et catégorie -->
                <div class="mb-4">
                    <span class="badge bg-primary mb-2">{{ ucfirst($realisation->category) }}</span>
                    <h1 class="display-5 fw-bold">{{ $realisation->title }}</h1>

                    @if($realisation->date)
                        <p class="text-muted">
                            <i class="far fa-calendar me-2"></i>
                            {{ \Carbon\Carbon::parse($realisation->date)->format('d F Y') }}
                        </p>
                    @endif
                </div>

                <!-- Description -->
                <div class="content-section mb-5">
                    <h3 class="mb-3">Description</h3>
                    <div class="text-justify">
                        {!! nl2br(e($realisation->description)) !!}
                    </div>
                </div>

                <!-- Galerie d'images -->
                @if($realisation->images && count($realisation->images) > 0)
                    <div class="mb-5">
                        <h3 class="mb-3">Galerie</h3>
                        <div class="row g-3">
                            @foreach($realisation->images as $image)
                                <div class="col-md-4">
                                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="gallery">
                                        <img src="{{ asset('storage/' . $image) }}"
                                             class="img-fluid rounded"
                                             alt="Image"
                                             style="height: 200px; width: 100%; object-fit: cover;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Boutons de partage -->
                <div class="d-flex gap-2 mb-4">
                    <span class="fw-bold me-2">Partager:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                       target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $realisation->title }}"
                       target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://wa.me/?text={{ $realisation->title }} {{ url()->current() }}"
                       target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Réalisations similaires -->
                @if($relatedRealisations->count() > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Réalisations similaires</h5>
                        </div>
                        <div class="card-body">
                            @foreach($relatedRealisations as $related)
                                <div class="mb-3 pb-3 border-bottom">
                                    @if($related->image)
                                        <img src="{{ asset('storage/' . $related->image) }}"
                                             class="img-fluid rounded mb-2"
                                             alt="{{ $related->title }}"
                                             style="height: 120px; width: 100%; object-fit: cover;">
                                    @endif
                                    <h6>
                                        <a href="{{ route('realisations.show', $related->slug) }}"
                                           class="text-decoration-none text-dark">
                                            {{ $related->title }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ Str::limit($related->description, 60) }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Call to Action -->
                {{-- <div class="card shadow-sm bg-primary text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Besoin d'une consultation ?</h5>
                        <p class="card-text">Prenez rendez-vous avec Adja Amsetou</p>
                        <a href="{{ route('appointments.create') }}" class="btn btn-light btn-lg">
                            Prendre RDV
                        </a>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</section>
@endsection
