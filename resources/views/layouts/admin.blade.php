<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') - TradiPratic</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #2d6a4f;
            --secondary-color: #40916c;
            --accent-color: #d4af37;
            --sidebar-bg: #1a1a2e;
            --sidebar-hover: #16213e;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }

        /* ============================================
           SIDEBAR
        ============================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: white;
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        .sidebar .logo {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.2);
        }

        .sidebar .logo h4 {
            color: var(--accent-color);
            font-weight: 700;
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
        }

        .sidebar .logo small {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* User Profile */
        .sidebar-user {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.15);
        }

        .sidebar-user .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            border: 2px solid var(--accent-color);
        }

        .sidebar-user .user-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
        }

        .sidebar-user .user-role {
            font-size: 0.75rem;
            color: var(--accent-color);
            margin: 0;
        }

        /* Navigation Sections */
        .nav-section {
            margin-top: 1.5rem;
            padding: 0 1.5rem;
        }

        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.4);
            margin-bottom: 0.75rem;
            padding-left: 0.5rem;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            transition: all 0.3s ease;
            border-radius: 8px;
            position: relative;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: var(--accent-color);
            border-radius: 0 2px 2px 0;
            transition: height 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: var(--sidebar-hover);
            padding-left: 1.25rem;
        }

        .sidebar .nav-link.active {
            color: white;
            background: linear-gradient(90deg, rgba(45, 106, 79, 0.3), transparent);
            border-left: 3px solid var(--accent-color);
            font-weight: 600;
        }

        .sidebar .nav-link.active::before {
            height: 100%;
        }

        .sidebar .nav-link i {
            width: 24px;
            margin-right: 12px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar .nav-link .badge {
            margin-left: auto;
            font-size: 0.7rem;
        }

        /* Submenu */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            padding-left: 2.5rem;
        }

        .submenu.show {
            max-height: 500px;
        }

        .submenu .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        /* ============================================
           MAIN CONTENT
        ============================================ */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #e9ecef;
        }

        .top-navbar .breadcrumb {
            margin: 0;
            background: transparent;
            padding: 0;
            font-size: 0.85rem;
        }

        .top-navbar .user-menu .dropdown-toggle {
            background: none;
            border: none;
            color: #495057;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .top-navbar .user-menu .dropdown-toggle:hover {
            background: #f8f9fa;
        }

        /* Page Header */
        .page-header {
            padding: 2rem;
            background: white;
            margin-bottom: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .page-header p {
            color: #6c757d;
            margin: 0.5rem 0 0 0;
        }

        /* Content Area */
        .content-area {
            padding: 0 2rem 2rem 2rem;
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .stat-card:hover::before {
            width: 6px;
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: #2c3e50;
        }

        .stat-card p {
            color: #6c757d;
            margin: 0.25rem 0 0 0;
            font-size: 0.9rem;
        }

        .stat-card .trend {
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Cards */
        .custom-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .custom-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .custom-card .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
            font-weight: 600;
            border-radius: 12px 12px 0 0;
        }

        /* Tables */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .custom-table {
            background: white;
            margin: 0;
        }

        .custom-table thead {
            background: #f8f9fa;
        }

        .custom-table th {
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            padding: 1rem;
        }

        .custom-table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f5;
        }

        .custom-table tbody tr {
            transition: all 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Badges */
        .badge-status {
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* Buttons */
        .btn-custom {
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 106, 79, 0.3);
        }

        /* Action Buttons */
        .table-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        /* Alerts */
        .alert-custom {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            border-left: 4px solid;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .content-area {
                padding: 0 1rem 1rem 1rem;
            }

            .top-navbar {
                padding: 1rem;
            }
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.show {
            display: flex;
        }

        .spinner-custom {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-custom"></div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Logo -->
        <div class="logo">
            <h4>TradiPratic</h4>
            <small>Plateforme de Gestion</small>
        </div>

        <!-- User Profile -->
        <div class="sidebar-user d-flex align-items-center">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="user-info ms-3 flex-grow-1">
                <h6>{{ Auth::user()->name }}</h6>
                <p class="user-role">
                    @if(Auth::user()->hasRole('super_admin'))
                        Super Administrateur
                    @elseif(Auth::user()->hasRole('admin'))
                        Administrateur
                    @elseif(Auth::user()->hasRole('manager'))
                        Gestionnaire
                    @elseif(Auth::user()->hasRole('receptionist'))
                        Réceptionniste
                    @elseif(Auth::user()->hasRole('customer_service'))
                        Service Client
                    @endif
                </p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="pb-4">
            <!-- Dashboard -->
            <div class="nav-section">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a>
            </div>

            <!-- Système de Rendez-vous -->
            @if(Auth::user()->hasPermission('appointments.view') || Auth::user()->hasPermission('availabilities.view') || Auth::user()->hasPermission('events.view'))
            <div class="nav-section">
                <div class="nav-section-title">Rendez-vous & Planning</div>

                @if(Auth::user()->hasPermission('availabilities.view'))
                <a class="nav-link {{ request()->routeIs('admin.availabilities.*') ? 'active' : '' }}" href="{{ route('admin.availabilities.index') }}">
                    <i class="fas fa-clock"></i>
                    <span>Disponibilités</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('events.view'))
                <a class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}" href="{{ route('admin.events.index') }}">
                    <i class="fas fa-calendar"></i>
                    <span>Événements</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('appointments.view'))
                <a class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}" href="{{ route('admin.appointments.index') }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Rendez-vous</span>
                    @if(isset($pendingAppointments) && $pendingAppointments > 0)
                    <span class="badge bg-warning">{{ $pendingAppointments }}</span>
                    @endif
                </a>
                @endif
            </div>
            @endif

            <!-- Contenu Plateforme -->
            @if(Auth::user()->hasPermission('realisations.view') || Auth::user()->hasPermission('recipes.view') || Auth::user()->hasPermission('testimonials.view'))
            <div class="nav-section">
                <div class="nav-section-title">Contenu</div>

                @if(Auth::user()->hasPermission('realisations.view'))
                <a class="nav-link {{ request()->routeIs('admin.realisations.*') ? 'active' : '' }}" href="{{ route('admin.realisations.index') }}">
                    <i class="fas fa-images"></i>
                    <span>Réalisations</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('recipes.view'))
                <a class="nav-link {{ request()->routeIs('admin.recipes.*') ? 'active' : '' }}" href="{{ route('admin.recipes.index') }}">
                    <i class="fas fa-utensils"></i>
                    <span>Recettes</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('testimonials.view'))
                <a class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">
                    <i class="fas fa-comments"></i>
                    <span>Témoignages</span>
                    @if(isset($pendingTestimonials) && $pendingTestimonials > 0)
                    <span class="badge bg-info">{{ $pendingTestimonials }}</span>
                    @endif
                </a>
                @endif

                @if(Auth::user()->hasPermission('media_images.view') || Auth::user()->hasPermission('media_videos.view'))
                    <a class="nav-link {{ request()->routeIs('admin.media*') ? 'active' : '' }}"
                    href="{{ route('admin.media.index') }}">
                        <i class="fas fa-photo-video"></i>
                        <span>Médias</span>
                        <span class="badge bg-success ms-auto">Nouveau</span>
                    </a>
                @endif

                @if(Auth::user()->hasPermission('bibliography.view'))
                <a class="nav-link {{ request()->routeIs('admin.bibliography.*') ? 'active' : '' }}" href="{{ route('admin.bibliography.index') }}">
                    <i class="fas fa-user-md"></i>
                    <span>Bibliographie</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('pub-services.view'))
                <a class="nav-link {{ request()->routeIs('admin.pub-services.*') ? 'active' : '' }}" href="{{ route('admin.pub-services.index') }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Publicités Services</span>
                    @if(isset($pendingPubServices) && $pendingPubServices > 0)
                    <span class="badge bg-warning">{{ $pendingPubServices }}</span>
                    @endif
                </a>
                @endif
            </div>
            @endif

            <!-- Hôtels & Réservations -->
            @if(Auth::user()->hasPermission('hotels.view') || Auth::user()->hasPermission('reservations.view'))
            <div class="nav-section">
                <div class="nav-section-title">Hôtellerie</div>

                @if(Auth::user()->hasPermission('hotels.view'))
                <a class="nav-link {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}" href="{{ route('admin.hotels.index') }}">
                    <i class="fas fa-hotel"></i>
                    <span>Hôtels</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('reservations.view'))
                <a class="nav-link {{ request()->routeIs('admin.hotel-reservations.*') ? 'active' : '' }}" href="{{ route('admin.hotel-reservations.index') }}">
                    <i class="fas fa-bed"></i>
                    <span>Réservations</span>
                    @if(isset($pendingReservations) && $pendingReservations > 0)
                    <span class="badge bg-warning">{{ $pendingReservations }}</span>
                    @endif
                </a>
                @endif
            </div>
            @endif

            <!-- E-Commerce -->
            @if(Auth::user()->hasPermission('products.view') || Auth::user()->hasPermission('orders.view'))
            <div class="nav-section">
                <div class="nav-section-title">E-Commerce</div>

                @if(Auth::user()->hasPermission('products.view'))
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box"></i>
                    <span>Produits</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('orders.view'))
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Commandes</span>
                    @if(isset($pendingOrders) && $pendingOrders > 0)
                    <span class="badge bg-info">{{ $pendingOrders }}</span>
                    @endif
                </a>
                @endif
            </div>
            @endif

            <!-- Dons & Donateurs -->
            @if(Auth::user()->hasPermission('donations.view'))
            <div class="nav-section">
                <div class="nav-section-title">Dons</div>

                <a class="nav-link {{ request()->routeIs('admin.donors.*') ? 'active' : '' }}" href="{{ route('admin.donors.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Donateurs</span>
                </a>

                <a class="nav-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}" href="{{ route('admin.donations.index') }}">
                    <i class="fas fa-hand-holding-heart"></i>
                    <span>Dons</span>
                </a>
            </div>
            @endif

            <!-- Paiements -->
            @if(Auth::user()->hasPermission('payments.view'))
            <div class="nav-section">
                <div class="nav-section-title">Finances</div>

                <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Paiements</span>
                </a>
            </div>
            @endif

            <!-- Communication -->
            @if(Auth::user()->hasPermission('contacts.view'))
            <div class="nav-section">
                <div class="nav-section-title">Communication</div>

                <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}" href="{{ route('admin.contacts.index') }}">
                    <i class="fas fa-envelope"></i>
                    <span>Messages</span>
                    @if(isset($newContacts) && $newContacts > 0)
                    <span class="badge bg-danger">{{ $newContacts }}</span>
                    @endif
                </a>
            </div>
            @endif

            <!-- Administration -->
            @if(Auth::user()->hasPermission('users.view') || Auth::user()->hasPermission('settings.view'))
            <div class="nav-section">
                <div class="nav-section-title">Administration</div>

                @if(Auth::user()->hasPermission('users.view'))
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users-cog"></i>
                    <span>Utilisateurs</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('roles.view'))
                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Rôles & Permissions</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('settings.view'))
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('logs.view'))
                <a class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}" href="{{ route('admin.activity-logs.index') }}">
                    <i class="fas fa-history"></i>
                    <span>Journaux d'activité</span>
                </a>
                @endif
            </div>
            @endif

            <!-- Autres -->
            <div class="nav-section">
                <div class="nav-section-title">Autre</div>

                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Voir le site</span>
                </a>

                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </nav>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary d-md-none me-3" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                <div class="user-menu dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fas fa-user me-2"></i> Mon profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Page Header -->
        @if(!isset($hidePageHeader))
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1>@yield('page-title', 'Administration')</h1>
                    <p>@yield('page-description', '')</p>
                </div>
                <div>
                    @yield('page-actions')
                </div>
            </div>
        </div>
        @endif

        <!-- Content Area -->
        <div class="content-area">
            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Erreurs de validation :</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Loading overlay helper
        window.showLoading = function() {
            document.getElementById('loadingOverlay').classList.add('show');
        };

        window.hideLoading = function() {
            document.getElementById('loadingOverlay').classList.remove('show');
        };

        // Confirm delete action
        window.confirmDelete = function(message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
            return confirm(message);
        };

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>

    @stack('scripts')
</body>
</html>
