{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="@yield('meta_description', 'Adja Amsetou - Tradi-praticienne à Komsilga, Burkina Faso. Consultations traditionnelles, prières, soins naturels.')">
    <meta name="keywords" content="@yield('meta_keywords', 'tradi-praticienne, médecine traditionnelle, Burkina Faso, Komsilga, consultations, prières')">
    <meta name="author" content="Adja Amsetou">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', 'Adja Amsetou - Tradi-praticienne')">
    <meta property="og:description" content="@yield('og_description', 'Consultations traditionnelles et soins naturels')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

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

    <!-- Styles Layout -->
    <style>
        /* ============================
           VARIABLES
           ============================ */
        :root {
            --tp-green: #198754;
            --tp-green-dark: #157347;
            --tp-green-light: #f0fdf4;
        }

        body {
            font-family: 'Open Sans', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        /* ============================
           TOP BAR
           ============================ */
        .top-bar {
            font-size: 0.85rem;
            background: linear-gradient(135deg, var(--tp-green) 0%, var(--tp-green-dark) 100%) !important;
        }

        .top-bar a {
            transition: opacity 0.2s;
        }

        .top-bar a:hover {
            opacity: 0.8;
        }

        .top-bar .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            transition: all 0.3s;
        }

        .top-bar .social-links a:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }

        /* ============================
           NAVBAR
           ============================ */
        .navbar {
            padding: 0.5rem 0;
            transition: all 0.3s;
        }

        .navbar.scrolled {
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .navbar .nav-link {
            font-weight: 500;
            color: #333;
            padding: 0.7rem 1rem !important;
            position: relative;
            transition: color 0.2s;
        }

        .navbar .nav-link:hover {
            color: var(--tp-green);
        }

        .navbar .nav-link.active {
            color: var(--tp-green) !important;
            font-weight: 600;
        }

        .navbar .nav-link:not(.live-link):not(.donate-nav-btn):not(.user-toggle)::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--tp-green);
            transition: all 0.3s;
            transform: translateX(-50%);
        }

        .navbar .nav-link:not(.live-link):not(.donate-nav-btn):not(.user-toggle):hover::after,
        .navbar .nav-link:not(.live-link):not(.donate-nav-btn):not(.user-toggle).active::after {
            width: 60%;
        }

        /* ============================
           🔴 LIVE INDICATOR
           ============================ */
        .live-link {
            display: flex !important;
            align-items: center;
            gap: 6px;
            padding: 6px 14px !important;
            border-radius: 20px;
            transition: all 0.3s;
            margin: 0 4px;
        }

        .live-link:hover {
            background: rgba(220, 53, 69, 0.1);
        }

        .live-link.active {
            background: rgba(220, 53, 69, 0.15);
        }

        .live-indicator {
            position: relative;
            display: inline-flex;
            width: 12px;
            height: 12px;
        }

        .live-dot {
            width: 10px;
            height: 10px;
            background: #dc3545;
            border-radius: 50%;
            position: absolute;
            top: 1px;
            left: 1px;
            z-index: 1;
            animation: liveBlink 1.5s infinite;
        }

        .live-pulse {
            width: 12px;
            height: 12px;
            background: rgba(220, 53, 69, 0.4);
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 0;
            animation: livePulse 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        .live-text {
            font-weight: 800;
            font-size: 0.75rem;
            color: #dc3545;
            letter-spacing: 1px;
            font-family: 'Open Sans', sans-serif;
        }

        @keyframes liveBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        @keyframes livePulse {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        /* ============================
           💚 BOUTON DON
           ============================ */
        .donate-nav-btn {
            background: linear-gradient(135deg, var(--tp-green), var(--tp-green-dark)) !important;
            color: white !important;
            border-radius: 20px !important;
            padding: 8px 18px !important;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .donate-nav-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.4);
            color: white !important;
        }

        .donate-nav-btn .fa-heart {
            color: #ff6b6b;
            animation: heartbeat 2s ease-in-out infinite;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            14% { transform: scale(1.3); }
            28% { transform: scale(1); }
            42% { transform: scale(1.3); }
            70% { transform: scale(1); }
        }

        /* ============================
           USER MENU
           ============================ */
        .user-toggle {
            display: flex !important;
            align-items: center;
            gap: 8px;
            padding: 4px 10px !important;
            border-radius: 50px;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .user-toggle:hover {
            background: var(--tp-green-light);
            border-color: var(--tp-green);
        }

        .user-avatar-nav {
            position: relative;
            flex-shrink: 0;
        }

        .user-avatar-nav img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border: 2px solid var(--tp-green);
            transition: transform 0.3s;
        }

        .user-avatar-nav:hover img {
            transform: scale(1.05);
        }

        .avatar-initials-nav {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--tp-green), var(--tp-green-dark));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            border: 2px solid var(--tp-green);
        }

        .avatar-initials-dropdown {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, var(--tp-green), var(--tp-green-dark));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .online-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background: #28a745;
            border: 2px solid white;
            border-radius: 50%;
        }

        /* User Dropdown */
        .user-dropdown-menu {
            min-width: 280px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            padding: 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.25s ease;
        }

        .user-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-header {
            background: linear-gradient(135deg, var(--tp-green-light), #dcfce7);
            padding: 20px;
            text-align: center;
        }

        .user-dropdown-menu .dropdown-item {
            padding: 10px 20px;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-dropdown-menu .dropdown-item:hover {
            background: var(--tp-green-light);
            padding-left: 24px;
        }

        .user-dropdown-menu .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .user-dropdown-menu .dropdown-item.text-danger:hover {
            background: #fef2f2;
        }

        /* Guest buttons */
        .guest-login-btn {
            border: 2px solid var(--tp-green);
            color: var(--tp-green);
            border-radius: 20px;
            padding: 6px 18px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .guest-login-btn:hover {
            background: var(--tp-green);
            color: white;
            transform: translateY(-1px);
        }

        /* ============================
           FLASH MESSAGES
           ============================ */
        .flash-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }

        .flash-container .alert {
            animation: slideInRight 0.4s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(60px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ============================
           FOOTER
           ============================ */
        .footer {
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%) !important;
        }

        .footer h5 {
            font-family: 'Playfair Display', serif;
            position: relative;
            padding-bottom: 10px;
        }

        .footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--tp-green);
        }

        .footer ul li {
            margin-bottom: 8px;
        }

        .footer ul li a {
            transition: all 0.2s;
            position: relative;
            padding-left: 0;
        }

        .footer ul li a:hover {
            color: #28a745 !important;
            padding-left: 5px;
        }

        .footer .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            transition: all 0.3s;
        }

        .footer .social-links a:hover {
            background: var(--tp-green);
            transform: translateY(-3px);
        }

        /* ============================
           WHATSAPP FLOAT
           ============================ */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #25D366;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: all 0.3s;
            text-decoration: none;
        }

        .whatsapp-float:hover {
            transform: scale(1.1) translateY(-3px);
            box-shadow: 0 6px 25px rgba(37, 211, 102, 0.5);
            color: white;
        }

        /* ============================
           SCROLL TO TOP
           ============================ */
        .scroll-to-top {
            position: fixed;
            bottom: 100px;
            right: 35px;
            width: 42px;
            height: 42px;
            background: var(--tp-green);
            color: white;
            border: none;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            z-index: 999;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .scroll-to-top.visible {
            display: flex;
        }

        .scroll-to-top:hover {
            background: var(--tp-green-dark);
            transform: translateY(-3px);
        }

        /* ============================
           RESPONSIVE
           ============================ */
        @media (max-width: 991.98px) {
            .top-bar .contact-info {
                text-align: center;
                font-size: 0.75rem;
            }

            .top-bar .social-links {
                text-align: center !important;
                margin-top: 5px;
            }

            .live-link {
                border-radius: 8px;
                margin: 4px 0;
                justify-content: center;
            }

            .donate-nav-btn {
                border-radius: 8px !important;
                margin: 4px 0;
                justify-content: center;
                width: 100%;
            }

            .user-toggle {
                border-radius: 8px;
                margin: 4px 0;
                justify-content: center;
            }

            .user-dropdown-menu {
                position: static !important;
                box-shadow: none;
                border: 1px solid #eee;
                margin-top: 5px;
            }

            .navbar .nav-link::after {
                display: none;
            }

            .guest-login-btn {
                width: 100%;
                justify-content: center;
                border-radius: 8px;
                margin: 4px 0;
            }
        }

        @media (max-width: 575.98px) {
            .top-bar {
                font-size: 0.75rem;
            }

            .whatsapp-float {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
                bottom: 20px;
                right: 20px;
            }

            .scroll-to-top {
                bottom: 80px;
                right: 25px;
                width: 36px;
                height: 36px;
            }
        }

        /* LIVE ACTIF - Animation renforcée */
        .live-active {
            background: rgba(220, 53, 69, 0.1) !important;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .live-count-badge {
            background: #dc3545;
            color: white;
            font-size: 0.55rem;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 8px;
            letter-spacing: 0.5px;
            animation: liveBlink 1.5s infinite;
        }

        /* LIVE IDLE */
        .live-idle .live-indicator-idle {
            color: #999;
            font-size: 0.85rem;
        }

        .live-idle .live-text-idle {
            font-weight: 700;
            font-size: 0.75rem;
            color: #999;
            letter-spacing: 1px;
        }

        .live-idle:hover .live-indicator-idle,
        .live-idle:hover .live-text-idle {
            color: #dc3545;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- ============================
         HEADER
         ============================ -->
    <header class="header">

        <!-- Top Bar -->
        <div class="top-bar bg-success text-white py-2 d-none d-md-block">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="contact-info">
                            <a href="{{ contact_phone_link() }}" class="text-white text-decoration-none me-3">
                                <i class="fas fa-phone-alt me-1"></i> {{ config('contact.phone') }}
                            </a>
                            <a href="{{ contact_email_link() }}" class="text-white text-decoration-none">
                                <i class="fas fa-envelope me-1"></i> {{ config('contact.email') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="social-links">
                            <a href="{{ config('contact.social.facebook') }}" target="_blank" title="Facebook">
                               <i class="fab fa-facebook-f text-white"></i>
                            </a>
                            <a href="{{ config('contact.social.youtube') }}" target="_blank" title="YouTube">
                                <i class="fab fa-youtube text-white"></i>
                            </a>
                            <a href="{{ config('contact.social.tiktok') }}" target="_blank" title="TikTok">
                                <i class="fab fa-tiktok text-white"></i>
                            </a>
                            <a href="{{ config('contact.social.instagram') }}" target="_blank" title="Instagram">
                                <i class="fab fa-instagram text-white"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
            <div class="container">

                <!-- Logo -->
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/icons/logo2.png') }}" alt="Adja Amsetou" height="55">
                </a>

                <!-- Toggler Mobile -->
                <button class="navbar-toggler border-0" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">

                    <!-- Menu Principal -->
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                               href="{{ route('home') }}">
                                <i class="fas fa-home d-lg-none me-1"></i>Accueil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                               href="{{ route('about') }}">
                                <i class="fas fa-info-circle d-lg-none me-1"></i>À Propos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('consultations') ? 'active' : '' }}"
                               href="{{ route('consultations') }}">
                                <i class="fas fa-stethoscope d-lg-none me-1"></i>Consultations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('realisations*') ? 'active' : '' }}"
                               href="{{ route('realisations.index') }}">
                                <i class="fas fa-images d-lg-none me-1"></i>Réalisations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('recipes*') ? 'active' : '' }}"
                               href="{{ route('recipes.index') }}">
                                <i class="fas fa-utensils d-lg-none me-1"></i>Recettes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('media') ? 'active' : '' }}"
                               href="{{ route('media') }}">
                                <i class="fas fa-photo-video d-lg-none me-1"></i>Médias
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                               href="{{ route('contact') }}">
                                <i class="fas fa-envelope d-lg-none me-1"></i>Contact
                            </a>
                        </li>
                    </ul>

                    <!-- Actions à droite -->
                    <div class="d-flex align-items-center ms-lg-3 flex-column flex-lg-row gap-2">

                        {{-- <!-- 🔴 LIVE YouTube -->
                        <a class="nav-link live-link {{ request()->routeIs('live') ? 'active' : '' }}"
                           href="{{ route('live') }}"
                           title="Regarder en direct">
                            <span class="live-indicator">
                                <span class="live-dot"></span>
                                <span class="live-pulse"></span>
                            </span>
                            <span class="live-text">LIVE</span>
                        </a> --}}
                        {{-- Dans layouts/app.blade.php — Remplacer le lien LIVE statique par : --}}

                        <!-- 🔴 LIVE YouTube (dynamique) -->
                        @if($hasActiveLive ?? false)
                            {{-- Un live est en cours --}}
                            <a class="nav-link live-link live-active {{ request()->routeIs('live') ? 'active' : '' }}"
                            href="{{ route('live') }}"
                            title="🔴 {{ $currentLive->title ?? 'En direct maintenant !' }}">
                                <span class="live-indicator">
                                    <span class="live-dot"></span>
                                    <span class="live-pulse"></span>
                                </span>
                                <span class="live-text">LIVE</span>
                                <span class="live-count-badge d-none d-lg-inline">EN COURS</span>
                            </a>
                        @else
                            {{-- Pas de live en cours --}}
                            <a class="nav-link live-link live-idle {{ request()->routeIs('live') ? 'active' : '' }}"
                            href="{{ route('live') }}"
                            title="Voir les lives et rediffusions">
                                <span class="live-indicator-idle">
                                    <i class="fas fa-video"></i>
                                </span>
                                <span class="live-text-idle">LIVE</span>
                            </a>
                        @endif

                        <!-- 💚 Faire Un Don -->
                        <a href="{{ route('donate') }}" class="donate-nav-btn">
                            <i class="fas fa-heart"></i>
                            <span>Faire Un Don</span>
                        </a>

                        <!-- ====================== -->
                        <!-- MENU UTILISATEUR       -->
                        <!-- ====================== -->
                        @auth
                            {{-- ===== CONNECTÉ ===== --}}
                            <div class="nav-item dropdown">
                                <a class="nav-link user-toggle"
                                   href="#"
                                   id="userDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   aria-expanded="false">

                                    <!-- Avatar -->
                                    <div class="user-avatar-nav">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ Auth::user()->avatar_url }}"
                                                 alt="{{ Auth::user()->full_name }}"
                                                 class="rounded-circle"
                                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'avatar-initials-nav\'>{{ Auth::user()->initials }}</div>';">
                                        @else
                                            <div class="avatar-initials-nav">
                                                {{ Auth::user()->initials }}
                                            </div>
                                        @endif
                                        <span class="online-dot"></span>
                                    </div>

                                    <!-- Nom (desktop) -->
                                    <div class="d-none d-lg-block lh-sm">
                                        <span class="fw-semibold small text-dark">{{ Auth::user()->prenom }}</span>
                                        @if(Auth::user()->roles->count() > 0)
                                            <br>
                                            <span class="text-muted" style="font-size: 0.65rem;">
                                                {{ Auth::user()->roles->first()->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <i class="fas fa-chevron-down d-none d-lg-inline" style="font-size: 0.6rem; color: #999;"></i>
                                </a>

                                <!-- Dropdown -->
                                <div class="dropdown-menu dropdown-menu-end user-dropdown-menu"
                                     aria-labelledby="userDropdown">

                                    <!-- Header du dropdown -->
                                    <div class="user-dropdown-header">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ Auth::user()->avatar_url }}"
                                                 alt="{{ Auth::user()->full_name }}"
                                                 class="rounded-circle shadow-sm mb-2"
                                                 width="55" height="55"
                                                 style="object-fit: cover; border: 3px solid white;">
                                        @else
                                            <div class="avatar-initials-dropdown mx-auto mb-2">
                                                {{ Auth::user()->initials }}
                                            </div>
                                        @endif
                                        <h6 class="mb-0 fw-bold">{{ Auth::user()->full_name }}</h6>
                                        <small class="text-muted">{{ Auth::user()->email }}</small>
                                        @if(Auth::user()->roles->count() > 0)
                                            <div class="mt-1">
                                                <span class="badge bg-success" style="font-size: 0.65rem;">
                                                    <i class="fas fa-user-tag me-1"></i>
                                                    {{ Auth::user()->roles->first()->name }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Mon Profil -->
                                    <a class="dropdown-item" href="{{ route('auth.profile.show') }}">
                                        <i class="fas fa-user-circle text-success"></i>
                                        Mon Profil
                                    </a>

                                   <hr class="dropdown-divider my-1">

                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt text-warning"></i>
                                            Tableau de bord
                                            <span class="badge bg-warning text-dark ms-auto" style="font-size: 0.6rem;">ADMIN</span>
                                        </a>

                                    <hr class="dropdown-divider my-1">

                                    <!-- Déconnexion -->
                                    <form method="POST" action="{{ route('auth.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>

                        @else
                            {{-- ===== NON CONNECTÉ ===== --}}
                            {{-- <a href="{{ route('login') }}" class="guest-login-btn">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Connexion</span>
                            </a> --}}
                            {{-- ====================== --}}
                            <div class="nav-item dropdown user-dropdown ms-2">

                                {{-- Bouton avatar --}}
                                <a class="nav-link d-flex align-items-center" href="{{ route('auth.login') }}">
                                    <img class="rounded-circle profile-img-xs"
                                        src="{{ Auth::check() && Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-profile.png') }}"
                                        alt="Profile image">
                                </a>

                                {{-- Dropdown --}}
                                {{-- <div class="dropdown-menu dropdown-menu-end navbar-dropdown" aria-labelledby="UserDropdown">

                                    <a class="dropdown-item" href="{{ route('auth.login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i> Connexion
                                    </a>

                                </div> --}}
                            </div>
                        @endauth


                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- ============================
         FLASH MESSAGES
         ============================ -->
    <div class="flash-container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="flashSuccess">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" id="flashError">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" id="flashWarning">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" id="flashInfo">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- ============================
         MAIN CONTENT
         ============================ -->
    <main>
        @yield('content')
    </main>

    <!-- ============================
         FOOTER
         ============================ -->
    <footer class="footer bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row">

                <!-- À propos -->
                <div class="col-md-4 mb-4">
                    <h5 class="text-success mb-3">Adja Amsetou</h5>
                    <p class="text-white-50">
                        Tradi-praticienne reconnue à Komsilga, spécialisée dans les
                        consultations traditionnelles, les prières et les soins naturels.
                    </p>
                    <div class="social-links mt-3">
                        <a href="{{ config('contact.social.facebook') }}" target="_blank" class="text-white me-2" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="{{ config('contact.social.youtube') }}" target="_blank" class="text-white me-2" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="{{ config('contact.social.tiktok') }}" target="_blank" class="text-white me-2" title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="{{ config('contact.social.instagram') }}" target="_blank" class="text-white" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Liens Rapides -->
                <div class="col-md-4 mb-4">
                    <h5 class="text-success mb-3">Liens Rapides</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('home') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-chevron-right me-1 small"></i>Accueil
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-chevron-right me-1 small"></i>À Propos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('consultations') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-chevron-right me-1 small"></i>Consultations
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('realisations.index') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-chevron-right me-1 small"></i>Réalisations
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('recipes.index') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-chevron-right me-1 small"></i>Recettes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('live') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-chevron-right me-1 small"></i>Live
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('donate') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-chevron-right me-1 small"></i>Faire un Don
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="text-white-50 text-decoration-none">
                                <i class="fas fa-chevron-right me-1 small"></i>Contact
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-md-4 mb-4">
                    <h5 class="text-success mb-3">Contact</h5>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                            Komsilga, Burkina Faso
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone-alt text-success me-2"></i>
                            <a href="{{ contact_phone_link() }}" class="text-white-50 text-decoration-none">
                                {{ config('contact.phone') }}
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="fab fa-whatsapp text-success me-2"></i>
                            <a href="{{ contact_whatsapp_link() }}" class="text-white-50 text-decoration-none">
                                WhatsApp
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope text-success me-2"></i>
                            <a href="mailto:{{ config('contact.email') }}" class="text-white-50 text-decoration-none">
                                {{ config('contact.email') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-secondary">

            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-white-50 small">
                        &copy; {{ date('Y') }} Adja Amsetou. Tous droits réservés.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3 small">Mentions Légales</a>
                    <a href="#" class="text-white-50 text-decoration-none small">Politique de Confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="{{ contact_whatsapp_link() }}" class="whatsapp-float" target="_blank" title="Contactez-nous sur WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Scroll to Top -->
    <button class="scroll-to-top" id="scrollToTop" title="Retour en haut">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- jQuery (avant Bootstrap JS pour compatibilité Summernote) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Scripts Layout -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ============================
            // Navbar scroll effect
            // ============================
            const navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // ============================
            // Scroll to top
            // ============================
            const scrollBtn = document.getElementById('scrollToTop');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 400) {
                    scrollBtn.classList.add('visible');
                } else {
                    scrollBtn.classList.remove('visible');
                }
            });

            scrollBtn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // ============================
            // Auto-dismiss flash messages
            // ============================
            document.querySelectorAll('.flash-container .alert').forEach(function(alert) {
                setTimeout(function() {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    if (bsAlert) bsAlert.close();
                }, 5000);
            });

            // ============================
            // Fermer navbar mobile au clic
            // ============================
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle):not(.user-toggle)');
            const navbarCollapse = document.getElementById('navbarNav');

            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992 && navbarCollapse.classList.contains('show')) {
                        var bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                        if (bsCollapse) bsCollapse.hide();
                    }
                });
            });

            // ============================
            // Active page highlight
            // ============================
            const currentPath = window.location.pathname;
            document.querySelectorAll('.navbar .nav-link').forEach(function(link) {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>













{{-- ============================================== --}}










