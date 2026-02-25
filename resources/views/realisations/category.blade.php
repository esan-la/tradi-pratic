@extends('layouts.app')

@section('title', 'Réalisations - ' . $categoryName)

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-success text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">{{ $categoryName }}</h1>
                <p class="lead mb-0">
                    Découvrez nos projets dans la catégorie {{ $categoryName }}
                </p>
                @if(isset($stats['total']))
                    <p class="mt-3 mb-0">
                        <span class="badge bg-white text-success fs-6">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ $stats['total'] }} projet{{ $stats['total'] > 1 ? 's' : '' }}
                        </span>
                    </p>
                @endif
            </div>
            <div class="col-lg-4 text-lg-end">
                <i class="fas fa-folder-open fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('realisations.index') }}">Réalisations</a></li>
            <li class="breadcrumb-item active">{{ $categoryName }}</li>
        </ol>
    </div>
</nav>

<!-- Catégories -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="{{ route('realisations.index') }}" class="btn btn-outline-success">
                <i class="fas fa-th me-1"></i> Toutes
            </a>
            @foreach($categories as $key => $cat)
                <a href="{{ route('realisations.category', $cat) }}"
                   class="btn {{ $category == $cat ? 'btn-success' : 'btn-outline-success' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Réalisations Grid -->
<section class="py-5">
    <div class="container">
        @if($realisations->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <p class="text-muted">
                        <strong>{{ $realisations->total() }}</strong> réalisation(s) dans cette catégorie
                    </p>
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
                                    $hasGallery = isset($realisation->gallery) &&
                                                 is_array($realisation->gallery) &&
                                                 count($realisation->gallery) > 0;
                                @endphp
                                @if($hasGallery)
                                    <span class="position-absolute bottom-0 start-0 m-3 badge bg-dark bg-opacity-75">
                                        <i class="fas fa-images me-1"></i>{{ count($realisation->gallery) + 1 }} photos
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
                                    @if($realisation->video_url)
                                        <div class="text-danger">
                                            <i class="fab fa-youtube me-1"></i>Vidéo
                                        </div>
                                    @endif
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
                    <nav aria-label="Navigation">
                        {{ $realisations->links() }}
                    </nav>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                        <h3 class="text-muted mb-3">Aucune réalisation dans cette catégorie</h3>
                        <p class="text-muted mb-4">
                            Explorez d'autres catégories ou revenez plus tard
                        </p>
                        <a href="{{ route('realisations.index') }}" class="btn btn-success">
                            <i class="fas fa-th me-2"></i> Voir toutes les réalisations
                        </a>
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
