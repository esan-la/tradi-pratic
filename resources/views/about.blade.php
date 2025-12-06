@extends('layouts.app')

@section('title', 'À Propos')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-dark text-white py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">À Propos d'Adja Amsetou</h1>
                <p class="lead">Tradi-praticienne reconnue au service de votre bien-être</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="{{ asset('images/image1.jpg') }}"
                     class="img-fluid rounded shadow"
                     alt="Adja Amsetou">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4">Qui est Adja Amsetou ?</h2>
                <p class="lead text-muted mb-4">
                    Tradi-praticienne expérimentée basée à Komsilga, Burkina Faso
                </p>
                <p>
                    Adja Amsetou est une tradi-praticienne reconnue qui exerce depuis plus de 20 ans.
                    Forte d'une connaissance approfondie de la médecine traditionnelle africaine,
                    elle met son expertise au service de tous ceux qui recherchent des solutions
                    naturelles et authentiques pour leur bien-être.
                </p>
                <p>
                    Spécialisée dans les consultations traditionnelles, les prières et les remèdes
                    naturels, Adja Amsetou accompagne chaque personne avec bienveillance et professionnalisme.
                </p>
            </div>
        </div>

        <!-- Services -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="mb-3">Nos Services</h2>
                <p class="text-muted">Des solutions traditionnelles pour votre bien-être</p>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <div class="icon-box bg-primary bg-opacity-10 rounded-circle mx-auto mb-3"
                             style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-md fa-2x text-primary"></i>
                        </div>
                        <h4>Consultations</h4>
                        <p class="text-muted">
                            Consultations personnalisées pour répondre à vos besoins spécifiques
                            en matière de santé et de bien-être.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <div class="icon-box bg-success bg-opacity-10 rounded-circle mx-auto mb-3"
                             style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-praying-hands fa-2x text-success"></i>
                        </div>
                        <h4>Prières</h4>
                        <p class="text-muted">
                            Séances de prières et rituels traditionnels pour la protection
                            spirituelle et le bien-être.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <div class="icon-box bg-warning bg-opacity-10 rounded-circle mx-auto mb-3"
                             style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-leaf fa-2x text-warning"></i>
                        </div>
                        <h4>Remèdes Naturels</h4>
                        <p class="text-muted">
                            Préparation de remèdes traditionnels à base de plantes médicinales
                            locales et naturelles.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valeurs -->
        <div class="row bg-light rounded p-5 mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="mb-3">Nos Valeurs</h2>
            </div>

            <div class="col-md-3 col-6 text-center mb-4">
                <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                <h5>Bienveillance</h5>
                <p class="small text-muted">Écoute et accompagnement personnalisé</p>
            </div>

            <div class="col-md-3 col-6 text-center mb-4">
                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                <h5>Authenticité</h5>
                <p class="small text-muted">Pratiques traditionnelles authentiques</p>
            </div>

            <div class="col-md-3 col-6 text-center mb-4">
                <i class="fas fa-star fa-3x text-warning mb-3"></i>
                <h5>Excellence</h5>
                <p class="small text-muted">Expertise et savoir-faire reconnus</p>
            </div>

            <div class="col-md-3 col-6 text-center mb-4">
                <i class="fas fa-handshake fa-3x text-success mb-3"></i>
                <h5>Confiance</h5>
                <p class="small text-muted">Confidentialité et respect</p>
            </div>
        </div>

        <!-- Activités complémentaires -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="mb-3">Nos Activités</h2>
                <p class="text-muted">Au-delà des consultations</p>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4><i class="fas fa-seedling text-success me-2"></i>Agriculture</h4>
                        <p class="text-muted">
                            Production de plantes médicinales et cultures biologiques pour
                            garantir la qualité de nos remèdes traditionnels.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4><i class="fas fa-palette text-primary me-2"></i>Artisanat</h4>
                        <p class="text-muted">
                            Création d'objets artisanaux traditionnels et d'accessoires
                            pour les rituels et cérémonies.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        {{-- <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card bg-primary text-white shadow-lg">
                    <div class="card-body text-center p-5">
                        <h3 class="mb-3">Prêt à commencer votre parcours de bien-être ?</h3>
                        <p class="lead mb-4">Prenez rendez-vous dès aujourd'hui</p>
                        <a href="{{ route('appointments.create') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-calendar-check me-2"></i>Prendre Rendez-vous
                        </a>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</section>
@endsection
