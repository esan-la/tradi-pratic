<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Adja Amsetou - Tradi-praticienne à Komsilga, Burkina Faso. Consultations traditionnelles, prières, soins naturels.')">
    <meta name="keywords" content="@yield('meta_keywords', 'tradi-praticienne, médecine traditionnelle, Burkina Faso, Komsilga, consultations, prières')">
    <meta name="author" content="Adja Amsetou">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', 'Adja Amsetou - Tradi-praticienne')">
    <meta property="og:description" content="@yield('og_description', 'Consultations traditionnelles et soins naturels')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
    <meta property="og:url" content="{{ url()->current() }}">

    <title>@yield('title', 'Accueil') - Adja Amsetou</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Header & Navigation -->
    <header class="header">
        <!-- Top Bar -->
        <div class="top-bar bg-success text-white py-2">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="contact-info">
                            <a href="tel:{{ env('WHATSAPP_NUMBER') }}" class="text-white text-decoration-none me-3">
                                <i class="fas fa-phone"></i> {{ env('WHATSAPP_NUMBER') }}
                            </a>
                            <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}" class="text-white text-decoration-none">
                                <i class="fas fa-envelope"></i> {{ env('MAIL_FROM_ADDRESS') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="social-links">
                            <a href="#" class="text-white me-2"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-tiktok"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/icons/logo2.png') }}" alt="Adja Amsetou" height="60">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">À Propos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('consultations') ? 'active' : '' }}" href="{{ route('consultations') }}">Consultations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('realisations*') ? 'active' : '' }}" href="{{ route('realisations') }}">Réalisations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('recipes*') ? 'active' : '' }}" href="{{ route('recipes') }}">Recettes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('media') ? 'active' : '' }}" href="{{ route('media') }}">Médias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-success ms-3">
                        <i class="fas fa-calendar-check"></i> Faire Un Don
                    </a>
                </div>
                {{-- ====================== --}}
                {{--      MENU UTILISATEUR  --}}
                {{-- ====================== --}}
                <div class="nav-item dropdown user-dropdown ms-2">

                    {{-- Bouton avatar --}}
                    <a class="nav-link d-flex align-items-center" id="UserDropdown" href="#" data-bs-toggle="dropdown">
                        <img class="rounded-circle profile-img-xs"
                            src="{{ Auth::check() && Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-profile.png') }}"
                            alt="Profile image">
                    </a>

                    {{-- Dropdown --}}
                    <div class="dropdown-menu dropdown-menu-end navbar-dropdown" aria-labelledby="UserDropdown">

                        @auth
                            {{-- HEADER --}}
                            <div class="dropdown-header text-center">
                                <img class="rounded-circle profile-img-md"
                                    src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-profile.png') }}"
                                    alt="Profile image">

                                <p class="mb-1 mt-3 fw-semibold">{{ Auth::user()->name }}</p>
                                <p class="fw-light text-muted mb-0">{{ Auth::user()->email }}</p>
                            </div>

                            {{-- MENU ITEMS --}}
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user-edit me-2"></i> Mon Profil
                            </a>

                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>

                            <hr class="dropdown-divider">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </button>
                            </form>

                        @else
                            <a class="dropdown-item" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Connexion
                            </a>

                            @if (Route::has('register'))
                                <a class="dropdown-item" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i> Inscription
                                </a>
                            @endif
                        @endauth

                    </div>
                </div>

                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-success mb-3">Adja Amsetou</h5>
                    <p>Tradi-praticienne reconnue à Komsilga, spécialisée dans les consultations traditionnelles, les prières et les soins naturels.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-youtube fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-tiktok fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-success mb-3">Liens Rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Accueil</a></li>
                        <li><a href="{{ route('about') }}" class="text-white text-decoration-none">À Propos</a></li>
                        <li><a href="{{ route('consultations') }}" class="text-white text-decoration-none">Consultations</a></li>
                        <li><a href="{{ route('realisations') }}" class="text-white text-decoration-none">Réalisations</a></li>
                        <li><a href="{{ route('recipes') }}" class="text-white text-decoration-none">Recettes</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                    {{-- <ul _ngcontent-rwx-c7="" class="navbar-nav ml-auto ml-md-0"><sb-top-nav-user _ngcontent-rwx-c7="" _nghost-rwx-c16=""><!---->
                        <li _ngcontent-rwx-c16="" class="nav-item dropdown-user no-caret dropdown" display="dynamic" ngbdropdown="" placement="bottom-right">
                            <a _ngcontent-rwx-c16="" aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle dropdown-toggle" data-cy="userMenu" id="userDropdown" ngbdropdowntoggle="" role="button"><fa-icon _ngcontent-rwx-c16="" class="ng-fa-icon"><svg role="img" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user" class="svg-inline--fa fa-user fa-w-14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"></path></svg></fa-icon></a>
                            <div _ngcontent-rwx-c16="" aria-labelledby="dropdownUser" class="dropdown-menu dropdown-menu-right" ngbdropdownmenu="" x-placement="bottom-right" style="top: 0px; left: 0px; will-change: transform;">
                                <h6 _ngcontent-rwx-c16="" class="dropdown-header"><div _ngcontent-rwx-c16="" class="dropdown-user-details">
                                    <div _ngcontent-rwx-c16="" class="dropdown-user-details-name">Start Bootstrap</div><div _ngcontent-rwx-c16="" class="dropdown-user-details-email">no-reply@startbootstrap.com</div>
                                    </div>
                                </h6>
                            <div _ngcontent-rwx-c16="" class="dropdown-divider"></div>
                            <a _ngcontent-rwx-c16="" class="dropdown-item" routerlink="/dashboard" href="/dashboard">Settings</a>
                            <a _ngcontent-rwx-c16="" class="dropdown-item" routerlink="/dashboard" href="/dashboard">Activity Log</a>
                            <div _ngcontent-rwx-c16="" class="dropdown-divider">
                                </div>
                                <a _ngcontent-rwx-c16="" class="dropdown-item" routerlink="/dashboard" href="/dashboard">Logout</a>
                            </div>
                        </li></sb-top-nav-user>
                    </ul> --}}
                    <!-- Authentication Links -->

                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-success mb-3">Contact</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt text-success"></i>
                            Komsilga, Burkina Faso
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone text-success"></i>
                            <a href="tel:{{ env('WHATSAPP_NUMBER') }}" class="text-white text-decoration-none">
                                {{ env('WHATSAPP_NUMBER') }}
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="fab fa-whatsapp text-success"></i>
                            <a href="https://wa.me/{{ str_replace('+', '', env('WHATSAPP_NUMBER')) }}" class="text-white text-decoration-none">
                                WhatsApp
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope text-success"></i>
                            <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}" class="text-white text-decoration-none">
                                {{ env('MAIL_FROM_ADDRESS') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; {{ date('Y') }} Adja Amsetou. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white text-decoration-none me-3">Mentions Légales</a>
                    <a href="#" class="text-white text-decoration-none">Politique de Confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/{{ str_replace('+', '', env('WHATSAPP_NUMBER')) }}" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>

