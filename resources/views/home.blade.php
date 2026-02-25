@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/image1.jpg') }}" class="d-block w-100" alt="Adja Amsetou">
                <div class="carousel-caption">
                    <div class="container">
                        <h2 class="display-3 fw-bold mb-3">Bienvenue chez Adja Amsetou</h2>
                        <p class="lead mb-4">Tradi-praticienne reconnue à Komsilga</p>
                        <a href="{{ route('consultations') }}" class="btn btn-success btn-lg me-2">
                            <i class="fas fa-calendar-check"></i> Prendre Rendez-vous
                        </a>
                        <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-info-circle"></i> En Savoir Plus
                        </a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/image1.jpg') }}" class="d-block w-100" alt="Consultations">
                <div class="carousel-caption">
                    <div class="container">
                        <h2 class="display-4 fw-bold mb-3">Consultations Traditionnelles</h2>
                        <p class="lead mb-4">Soins naturels et prières personnalisées</p>
                        <a href="{{ route('consultations') }}" class="btn btn-success btn-lg">Découvrir nos Services</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/image1.jpg') }}" class="d-block w-100" alt="Traditions">
                <div class="carousel-caption">
                    <div class="container">
                        <h2 class="display-4 fw-bold mb-3">Traditions & Culture</h2>
                        <p class="lead mb-4">Recettes authentiques et savoir-faire ancestral</p>
                        <a href="{{ route('recipes.index') }}" class="btn btn-success btn-lg">Voir les Recettes</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Services & Actualités Section -->
<section class="services-section py-5 mt-3">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Services & Actualités</h2>
            <p class="text-muted">Découvrez nos derniers services et actualités</p>
        </div>

        @if($latestServices->count() > 0)
        <div class="position-relative">
            <div id="servicesCarousel" class="carousel slide" data-bs-ride="false">
                <div class="carousel-inner">
                    @foreach($latestServices->chunk(4) as $chunkIndex => $servicesChunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row g-4">
                            @foreach($servicesChunk as $service)
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('pub-services.show', $service->slug) }}" class="text-decoration-none">
                                    <div class="service-card-new h-100 shadow-sm">
                                        <div class="service-image-wrapper">
                                            <img src="{{ asset('storage/' . $service->image) }}"
                                                 alt="{{ $service->title }}"
                                                 class="img-fluid service-image"
                                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                                                 loading="lazy">
                                            @if($service->price)
                                            <span class="price-badge">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                                            @endif
                                        </div>
                                        <div class="service-content p-3">
                                            <h5 class="service-title mb-2">{{ Str::limit($service->title, 40) }}</h5>
                                            <p class="service-description text-muted small mb-2">
                                                {{ Str::limit($service->description, 80) }}
                                            </p>
                                            <div class="service-meta small text-muted">
                                                <i class="fas fa-user me-1"></i>{{ $service->user->name }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($latestServices->count() > 4)
                <button class="carousel-control-prev service-carousel-control" type="button" data-bs-target="#servicesCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon-custom" aria-hidden="true">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                </button>
                <button class="carousel-control-next service-carousel-control" type="button" data-bs-target="#servicesCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon-custom" aria-hidden="true">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </button>
                @endif
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('pub-services.index') }}" class="btn btn-success">
                Voir tous les services <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        @else
        <div class="text-center text-muted py-5">
            <i class="fas fa-inbox fa-3x mb-3"></i>
            <p>Aucun service disponible pour le moment.</p>
        </div>
        @endif
    </div>
</section>

<!-- About Preview -->
<section class="about-preview py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="{{ asset('images/image1.jpg') }}" alt="Adja Amsetou" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h2 class="mb-4">À Propos d'Adja Amsetou</h2>
                <p class="lead text-muted mb-4">
                    Tradi-praticienne reconnue et respectée, Adja Amsetou met son savoir ancestral
                    et son expérience au service de ceux qui cherchent guidance et bien-être.
                </p>
                <ul class="list-unstyled mb-4">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Plus de 05 ans d'expérience</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Reconnue par les autorités locales</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Approche respectueuse et bienveillante</li>
                </ul>
                <a href="{{ route('about') }}" class="btn btn-success">Découvrir mon parcours</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Realisations -->
<section class="realisations-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Nos Réalisations</h2>
            <p class="text-muted">Agriculture, élevage et artisanat traditionnel</p>
        </div>
        <div class="row g-4">
            @forelse($featuredRealisations as $realisation)
            <div class="col-md-4">
                <div class="realisation-card h-100">
                    <div class="realisation-image">
                        @if($realisation->image)
                            {{-- <img src="{{ asset('storage/' . $realisation->image) }}"
                                 alt="{{ $realisation->title }}"
                                 class="img-fluid realisation-img"
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                                 loading="lazy"> --}}
                            <img src="{{ asset('storage/' . $realisation->image) }}"
                                class="card-img-top w-100 h-100"
                                alt="{{ $realisation->title }}"
                                style="object-fit: cover; transition: transform 0.3s ease;"
                                onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                                loading="lazy">
                        @elseif($realisation->gallery && is_array($realisation->gallery) && count($realisation->gallery) > 0)
                            <img src="{{ asset('storage/' . $realisation->gallery[0]) }}"
                                 alt="{{ $realisation->title }}"
                                 class="img-fluid realisation-img"
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                                 loading="lazy">
                        @else
                            <img src="{{ asset('images/placeholder.jpg') }}"
                                 alt="{{ $realisation->title }}"
                                 class="img-fluid realisation-img"
                                 loading="lazy">
                        @endif
                    </div>
                    <div class="realisation-content p-3">
                        <span class="badge bg-success mb-2">{{ $realisation->category }}</span>
                        <h5>{{ $realisation->title }}</h5>
                        <p class="text-muted">{{ Str::limit($realisation->description, 100) }}</p>
                        <a href="{{ route('realisations.show', $realisation->slug) }}" class="btn btn-sm btn-outline-success">
                            Voir plus <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    <i class="fas fa-seedling fa-3x mb-3"></i>
                    <p>Aucune réalisation disponible pour le moment.</p>
                </div>
            </div>
            @endforelse
        </div>
        @if($featuredRealisations->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('realisations.index') }}" class="btn btn-success">Voir toutes les réalisations</a>
        </div>
        @endif
    </div>
</section>

<!-- Latest Recipes -->
<section class="recipes-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Recettes Traditionnelles</h2>
            <p class="text-muted">Découvrez les saveurs authentiques du Burkina Faso</p>
        </div>
        <div class="row g-4">
            @forelse($latestRecipes as $recipe)
            <div class="col-md-4">
                <div class="recipe-card h-100">
                    <div class="recipe-image">
                        @if($recipe->image)
                            <img src="{{ asset('storage/' . $recipe->image) }}"
                                 alt="{{ $recipe->title }}"
                                 class="img-fluid recipe-img"
                                 onerror="this.src='{{ asset('images/recipe-placeholder.jpg') }}'"
                                 loading="lazy">
                        @else
                            <img src="{{ asset('images/recipe-placeholder.jpg') }}"
                                 alt="{{ $recipe->title }}"
                                 class="img-fluid recipe-img"
                                 loading="lazy">
                        @endif
                    </div>
                    <div class="recipe-content p-3">
                        <h5>{{ $recipe->title }}</h5>
                        <p class="text-muted">{{ Str::limit($recipe->description, 80) }}</p>
                        <div class="recipe-meta small text-muted mb-3">
                            @if($recipe->prep_time)
                                <span class="me-3"><i class="fas fa-clock me-1"></i>{{ $recipe->prep_time }} min</span>
                            @endif
                            @if($recipe->servings)
                                <span><i class="fas fa-users me-1"></i>{{ $recipe->servings }} pers.</span>
                            @endif
                        </div>
                        <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-sm btn-outline-success">
                            Voir la recette <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    <i class="fas fa-utensils fa-3x mb-3"></i>
                    <p>Aucune recette disponible pour le moment.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Témoignages</h2>
            <p class="text-muted">Ce que disent nos clients</p>
        </div>
        <div class="row g-4">
            @forelse($testimonials as $testimonial)
            <div class="col-md-4">
                <div class="testimonial-card p-4 h-100">
                    <div class="stars mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                    </div>
                    <p class="testimonial-text">"{{ $testimonial->content }}"</p>
                    <div class="testimonial-author d-flex align-items-center mt-3">
                        @if($testimonial->avatar)
                            <img src="{{ asset('storage/' . $testimonial->avatar) }}"
                                 alt="{{ $testimonial->name }}"
                                 class="rounded-circle me-3 testimonial-avatar"
                                 width="50"
                                 height="50"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 loading="lazy">
                            <div class="avatar-placeholder rounded-circle me-3 d-none align-items-center justify-content-center"
                                 style="width: 50px; height: 50px; background: #2d6a4f; color: white;">
                                <strong>{{ strtoupper(substr($testimonial->name, 0, 1)) }}</strong>
                            </div>
                        @else
                            <div class="avatar-placeholder rounded-circle me-3 d-flex align-items-center justify-content-center"
                                 style="width: 50px; height: 50px; background: #2d6a4f; color: white;">
                                <strong>{{ strtoupper(substr($testimonial->name, 0, 1)) }}</strong>
                            </div>
                        @endif
                        <div>
                            <strong>{{ $testimonial->name }}</strong>
                            <div class="text-muted small">{{ $testimonial->location }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <p>Aucun témoignage disponible pour le moment.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section py-5 bg-success text-white">
    <div class="container text-center">
        <h2 class="mb-4">Prêt à commencer votre voyage vers le bien-être ?</h2>
        <p class="lead mb-4">Prenez rendez-vous dès aujourd'hui pour une consultation personnalisée</p>
        <a href="{{ route('consultations') }}" class="btn btn-light btn-lg me-2">
            <i class="fas fa-calendar-check"></i> Prendre Rendez-vous
        </a>
        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
            <i class="fas fa-envelope"></i> Nous Contacter
        </a>
    </div>
</section>
@endsection

@push('styles')
<style>
.service-card-new {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    cursor: pointer;
}

.service-card-new:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
}

.service-image-wrapper {
    position: relative;
    overflow: hidden;
    height: 200px;
    background: #f8f9fa;
}

.service-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
}

.service-card-new:hover .service-image {
    transform: scale(1.1);
}

.price-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(45, 106, 79, 0.95);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.service-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
}

.realisation-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.realisation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.realisation-image {
    height: 250px;
    overflow: hidden;
    background: #f8f9fa;
}

.realisation-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
}

.realisation-card:hover .realisation-img {
    transform: scale(1.05);
}

.recipe-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.recipe-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.recipe-image {
    height: 220px;
    overflow: hidden;
    background: #f8f9fa;
}

.recipe-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
}

.recipe-card:hover .recipe-img {
    transform: scale(1.05);
}

.testimonial-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.testimonial-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.testimonial-avatar {
    object-fit: cover;
    object-position: center;
    border: 2px solid #2d6a4f;
}

.service-carousel-control {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    opacity: 1;
    top: 50%;
    transform: translateY(-50%);
}

.service-carousel-control:hover {
    background: #2d6a4f;
}

.carousel-control-prev-icon-custom,
.carousel-control-next-icon-custom {
    color: #2d6a4f;
    font-size: 1.5rem;
}

.service-carousel-control:hover .carousel-control-prev-icon-custom,
.service-carousel-control:hover .carousel-control-next-icon-custom {
    color: white;
}

/* Amélioration qualité images */
img {
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
}
</style>
@endpush
