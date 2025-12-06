@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-success text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">Contactez-nous</h1>
                <p class="lead">Nous sommes à votre écoute</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Formulaire de contact -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Envoyez-nous un message</h3>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nom complet *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">Sujet *</label>
                                    <input type="text"
                                           class="form-control @error('subject') is-invalid @enderror"
                                           id="subject"
                                           name="subject"
                                           value="{{ old('subject') }}"
                                           required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message"
                                          name="message"
                                          rows="6"
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="col-lg-4">
                <!-- Coordonnées -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="mb-4">Nos Coordonnées</h4>

                        <div class="mb-3">
                            <h6><i class="fas fa-map-marker-alt text-danger me-2"></i>Adresse</h6>
                            <p class="text-muted">Komsilga, Burkina Faso</p>
                        </div>

                        <div class="mb-3">
                            <h6><i class="fas fa-phone text-success me-2"></i>Téléphone</h6>
                            <p class="text-muted">
                                <a href="tel:+22670123456" class="text-decoration-none">+226 70 12 34 56</a>
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6><i class="fas fa-envelope text-primary me-2"></i>Email</h6>
                            <p class="text-muted">
                                <a href="mailto:contact@adja-amsetou.com" class="text-decoration-none">
                                    contact@adja-amsetou.com
                                </a>
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6><i class="fas fa-clock text-warning me-2"></i>Horaires</h6>
                            <p class="text-muted mb-1">Lundi - Jeudi: 09h - 18h</p>
                            <p class="text-muted mb-0">Samedi: 9h - 18h</p>
                        </div>
                    </div>
                </div>

                <!-- Réseaux sociaux -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="mb-3">Suivez-nous</h4>
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-outline-primary" target="_blank">
                                <i class="fab fa-facebook-f me-2"></i>Facebook
                            </a>
                            <a href="#" class="btn btn-outline-danger" target="_blank">
                                <i class="fab fa-youtube me-2"></i>YouTube
                            </a>
                            <a href="#" class="btn btn-outline-info" target="_blank">
                                <i class="fab fa-tiktok me-2"></i>TikTok
                            </a>
                            <a href="#" class="btn btn-outline-dark" target="_blank">
                                <i class="fab fa-instagram me-2"></i>Instagram
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CTA Rendez-vous -->
                {{-- <div class="card shadow-sm bg-primary text-white">
                    <div class="card-body text-center">
                        <h5 class="mb-3">Besoin d'un rendez-vous ?</h5>
                        <p>Réservez votre consultation en ligne</p>
                        <a href="{{ route('appointments.create') }}" class="btn btn-light">
                            Prendre RDV
                        </a>
                    </div>
                </div> --}}
            </div>
        </div>

        <!-- Carte (optionnel) -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div id="map" style="height: 400px; width: 100%;">
                            <!-- Intégrer Google Maps ou OpenStreetMap ici -->
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d123456.789!2d-1.5!3d12.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTLCsDMwJzAwLjAiTiAxwrAzMCcwMC4wIlc!5e0!3m2!1sfr!2sbf!4v1234567890"
                                    width="100%"
                                    height="400"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
