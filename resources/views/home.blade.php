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
                        <h1 class="display-3 fw-bold mb-3">Bienvenue chez Adja Amsetou</h1>
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
                        <a href="{{ route('recipes') }}" class="btn btn-success btn-lg">Voir les Recettes</a>
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

{{-- <section class="hero-section position-relative">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/hero-1.jpg') }}" class="d-block w-100" alt="Adja Amsetou">
                <div class="carousel-caption">
                    <div class="container">
                        <h1 class="display-3 fw-bold mb-3">Bienvenue chez Adja Amsetou</h1>
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
                <img src="{{ asset('images/hero-2.jpg') }}" class="d-block w-100" alt="Consultations">
                <div class="carousel-caption">
                    <div class="container">
                        <h2 class="display-4 fw-bold mb-3">Consultations Traditionnelles</h2>
                        <p class="lead mb-4">Soins naturels et prières personnalisées</p>
                        <a href="{{ route('consultations') }}" class="btn btn-success btn-lg">Découvrir nos Services</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/hero-3.jpg') }}" class="d-block w-100" alt="Traditions">
                <div class="carousel-caption">
                    <div class="container">
                        <h2 class="display-4 fw-bold mb-3">Traditions & Culture</h2>
                        <p class="lead mb-4">Recettes authentiques et savoir-faire ancestral</p>
                        <a href="{{ route('recipes') }}" class="btn btn-success btn-lg">Voir les Recettes</a>
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
</section> --}}

<!-- Services Section -->
<section class="services-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Nos Services</h2>
            <p class="text-muted">Des solutions traditionnelles adaptées à vos besoins</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card text-center p-4 h-100">
                    <div class="service-icon mb-3">
                        <i class="fas fa-hands -praying fa-3x text-success"></i>
                    </div>
                    <h4>Consultations Traditionnelles</h4>
                    <p class="text-muted">Guidance spirituelle et conseils personnalisés basés sur les traditions ancestrales.</p>
                    <a href="{{ route('consultations') }}" class="btn btn-outline-success">En savoir plus</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card text-center p-4 h-100">
                    <div class="service-icon mb-3">
                        <i class="fas fa-leaf fa-3x text-success"></i>
                    </div>
                    <h4>Soins Naturels</h4>
                    <p class="text-muted">Remèdes naturels et plantes médicinales pour votre bien-être physique et mental.</p>
                    <a href="{{ route('consultations') }}" class="btn btn-outline-success">En savoir plus</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card text-center p-4 h-100">
                    <div class="service-icon mb-3">
                        <i class="fas fa-moon fa-3x text-success"></i>
                    </div>
                    <h4>Prières & Rituels</h4>
                    <p class="text-muted">Cérémonies traditionnelles et prières pour la protection et la prospérité.</p>
                    <a href="{{ route('consultations') }}" class="btn btn-outline-success">En savoir plus</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Preview Section -->
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
                <p class="mb-4">
                    Basée à Komsilga au Burkina Faso, elle combine traditions authentiques et
                    approche moderne pour offrir des consultations personnalisées, des soins naturels
                    et des prières adaptées à chaque situation.
                </p>
                <ul class="list-unstyled mb-4">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Plus de 05 ans d'expérience</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Reconnue par les autorités locales</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Approche respectueuse et bienveillante</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Engagement communautaire fort</li>
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
            @foreach($featuredRealisations as $realisation)
            <div class="col-md-4">
                <div class="realisation-card h-100">
                    <div class="realisation-image">
                        <img src="{{ $realisation->images[0] ?? asset('images/placeholder.jpg') }}"
                        alt="{{ $realisation->title }}"
                        class="img-fluid">
                        {{-- <img src="{{ $realisation->images,[object Object], ?? asset('images/placeholder.jpg') }}"
                             alt="{{ $realisation->title }}"
                             class="img-fluid"> --}}
                        <span class="category-badge">{{ $realisation->category_name }}</span>
                    </div>
                    <div class="realisation-content p-3">
                        <h5>{{ $realisation->title }}</h5>
                        <p class="text-muted">{{ Str::limit($realisation->description, 100) }}</p>
                        <a href="{{ route('realisations.show', $realisation->slug) }}" class="btn btn-sm btn-outline-success">
                            Voir plus <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('realisations') }}" class="btn btn-success">Voir toutes les réalisations</a>
        </div>
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
            @foreach($latestRecipes as $recipe)
            <div class="col-md-4">
                <div class="recipe-card h-100">
                    <div class="recipe-image">
                        <img src="{{ $recipe->featured_image ?? asset('images/recipe-placeholder.jpg') }}"
                             alt="{{ $recipe->title }}"
                             class="img-fluid">
                        @if($recipe->youtube_video_id)
                        <span class="video-badge"><i class="fab fa-youtube"></i></span>
                        @endif
                    </div>
                    <div class="recipe-content p-3">
                        <h5>{{ $recipe->title }}</h5>
                        <div class="recipe-meta mb-3">
                            <span class="me-3"><i class="far fa-clock text-success"></i> {{ $recipe->preparation_time }}</span>
                            <span><i class="fas fa-utensils text-success"></i> {{ $recipe->servings }} pers.</span>
                        </div>
                        <p class="text-muted">{{ Str::limit($recipe->description, 80) }}</p>
                        <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-sm btn-outline-success">
                            Voir la recette <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('recipes') }}" class="btn btn-success">Voir toutes les recettes</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Témoignages</h2>
            <p class="text-muted">Ce que disent nos clients</p>
        </div>
        <div class="row g-4">
            @foreach($testimonials as $testimonial)
            <div class="col-md-4">
                <div class="testimonial-card p-4 h-100">
                    <div class="stars mb-3">
                        {!! $testimonial->stars_html !!}
                    </div>
                    <p class="testimonial-text">"{{ $testimonial->content }}"</p>
                    <div class="testimonial-author d-flex align-items-center mt-3">
                        <img src="{{ $testimonial->avatar ?? asset('images/avatar-default.png') }}"
                             alt="{{ $testimonial->name }}"
                             class="rounded-circle me-3"
                             width="50"
                             height="50">
                        <div>
                            <strong>{{ $testimonial->name }}</strong>
                            <div class="text-muted small">{{ $testimonial->location }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action -->
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroSection = document.querySelector('.hero-section');
    const carouselElement = document.getElementById('heroCarousel');
    const carousel = new bootstrap.Carousel(carouselElement);

    // ========================================
    // CLIQUER SUR LA SECTION CHANGE LE SLIDE
    // ========================================
    if(heroSection) {
        heroSection.addEventListener('click', function() {
            carousel.next();
        });
    }

    // ========================================
    // OPTIONNEL : Couleur dynamique si besoin
    // ========================================
    // Si tu veux gérer plusieurs couleurs selon fond (ici tout vert)
    const activeItem = carouselElement.querySelector('.carousel-item.active');
    if(activeItem) {
        const headings = activeItem.querySelectorAll('h1, h2');
        headings.forEach(h => h.style.color = '#2D7A3E');
    }

    // Mise à jour à chaque slide
    carouselElement.addEventListener('slid.bs.carousel', function(e){
        const current = e.to ? e.to : carouselElement.querySelector('.carousel-item.active');
        const headings = current.querySelectorAll('h1, h2');
        headings.forEach(h => h.style.color = '#2D7A3E');
    });
});


</script>
@endpush
