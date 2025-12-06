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

<!-- Filter Section -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    @foreach($categories as $key => $label)
                        <a href="{{ route('realisations', ['category' => $key]) }}"
                           class="btn {{ request('category', 'all') == $key ? 'btn-success' : 'btn-outline-success' }}">
                            {{ $label }}
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
                        @if(request('category') && request('category') != 'all')
                            dans la catégorie "<strong>{{ $categories[request('category')] ?? '' }}</strong>"
                        @endif
                    </p>
                </div>
            </div>

            <div class="row g-4">
                @foreach($realisations as $realisation)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm realisation-card">
                            @if($realisation->image)
                                <div class="position-relative overflow-hidden" style="height: 250px;">
                                    <img src="{{ asset('storage/' . $realisation->image) }}"
                                         class="card-img-top w-100 h-100"
                                         alt="{{ $realisation->title }}"
                                         style="object-fit: cover;">
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-success">
                                        {{ ucfirst($realisation->category) }}
                                    </span>
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <i class="fas fa-image fa-4x text-muted"></i>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="{{ route('realisations.show', $realisation->slug) }}"
                                       class="text-decoration-none text-dark stretched-link">
                                        {{ $realisation->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted flex-grow-1">
                                    {{ Str::limit($realisation->description, 100) }}
                                </p>

                                @if($realisation->date)
                                    <div class="text-muted small mt-3">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($realisation->date)->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>

                            <div class="card-footer bg-white border-0 pt-0">
                                <a href="{{ route('realisations.show', $realisation->slug) }}"
                                   class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-arrow-right me-1"></i> En savoir plus
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    {{ $realisations->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                        <h3 class="text-muted">Aucune réalisation trouvée</h3>
                        <p class="text-muted mb-4">
                            @if(request('category') && request('category') != 'all')
                                Essayez une autre catégorie
                            @else
                                Revenez plus tard pour découvrir nos projets
                            @endif
                        </p>
                        @if(request('category') && request('category') != 'all')
                            <a href="{{ route('realisations') }}" class="btn btn-success">
                                <i class="fas fa-redo me-2"></i> Voir toutes les réalisations
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
}

.realisation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.realisation-card .stretched-link::after {
    z-index: 0;
}

.realisation-card .btn {
    position: relative;
    z-index: 1;
}
</style>
@endpush
