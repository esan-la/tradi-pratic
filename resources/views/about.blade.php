@extends('layouts.app')

@section('title', 'À Propos')

@section('content')
<!-- Hero Section -->
{{-- <section class="hero-section bg-dark text-white py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"> --}}
<section class="hero-section bg-success text-white py-5">
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

        <div class="mb-5">

        <!-- Image fixée en haut à gauche -->
        <img src="{{ asset('images/image1.jpg') }}"
            class="img-fluid rounded shadow float-lg-start me-lg-4 mb-3"
            style="max-width: 380px;"
            alt="Adja Amsetou, guérisseuse traditionnelle à Komsilga">

        <h2 class="mb-4">Qui est Adja Amsetou de Komsilga ?</h2>

        <p class="lead text-muted mb-4">
            Guérisseuse traditionnelle basée à Komsilga, Burkina Faso
        </p>

        <p>
            Installée à Komsilga, au Burkina Faso, Adja Amsetou est reconnue comme une
            guérisseuse traditionnelle dotée de puissances spirituelles remarquables.
            Figure respectée dans son milieu, elle s’inscrit dans la longue tradition
            africaine de la médecine ancestrale, où les savoirs mystiques, les pratiques
            thérapeutiques naturelles et la guidance spirituelle se conjuguent au service
            du bien-être de la personne.
        </p>

        <p>
            Depuis 2019, Adja Amsetou accompagne des personnes venues de divers horizons,
            à la recherche de solutions face à des problèmes de santé, des blocages
            spirituels, des difficultés conjugales ou encore des situations
            professionnelles complexes. Selon les témoignages de ses consultants,
            son approche repose avant tout sur une écoute attentive, une consultation
            spirituelle approfondie et l’utilisation de rituels et de remèdes issus
            des traditions locales.
        </p>

        <h5 class="mt-4 mb-3">Une approche enracinée dans la tradition</h5>

        <p>
            Le travail d’Adja Amsetou s’appuie sur les fondements de la tradition
            ouest-africaine, transmise de génération en génération. Son savoir-faire
            repose notamment sur :
        </p>

        <ul>
            <li>
                Les savoirs ancestraux transmis au fil des générations
            </li>
            <li>
                L’usage de plantes médicinales et de préparations traditionnelles
            </li>
            <li>
                Des pratiques spirituelles destinées à rétablir l’équilibre entre
                l’individu et son environnement
            </li>
        </ul>

        <p>
            Dans la tradition ouest-africaine, la guérison ne se limite pas uniquement
            au corps. Elle englobe également l’âme, l’esprit et les relations sociales.
            Adja Amsetou adopte pleinement cette vision holistique, considérant que de
            nombreux maux trouvent leur origine dans des déséquilibres invisibles
            nécessitant une prise en charge globale.
        </p>

        <h5 class="mt-4 mb-3">Une écoute et un accompagnement personnalisés</h5>

        <p>
            Chaque consultation est conçue comme un moment privilégié d’échange et
            de confidentialité. Les personnes qui la consultent évoquent sa discrétion,
            sa disponibilité et son engagement constant à rechercher des solutions
            adaptées à chaque situation, dans le respect des traditions et des valeurs
            humaines.
        </p>

        <div class="clearfix"></div>
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
