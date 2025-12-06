@extends('layouts.app')

@section('title', 'Consultations & Prières')

@section('content')
<!-- Page Header -->
<section class="page-header py-5 bg-light">
    <div class="container">
        <h1 class="display-4 mb-3">Consultations & Prières</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item active">Consultations</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Services Details -->
<section class="services-details py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="service-detail-card mb-4">
                    <h3 class="mb-4">Nos Services de Consultation</h3>

                    <div class="accordion" id="servicesAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#service1">
                                    <i class="fas fa-hands-praying text-success me-2"></i>
                                    Consultations Traditionnelles
                                </button>
                            </h2>
                            <div id="service1" class="accordion-collapse collapse show" data-bs-parent="#servicesAccordion">
                                <div class="accordion-body">
                                    <p>Guidance spirituelle personnalisée basée sur les traditions ancestrales burkinabè.</p>
                                    <ul>
                                        <li>Consultation individuelle (15 minutes) - 500 FCFA</li>
                                        <li>Consultation familiale (30 minutes) - 1 000 FCFA</li>
                                        <li>Consultation approfondie (1h) - 2 000 FCFA</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service2">
                                    <i class="fas fa-moon text-success me-2"></i>
                                    Prières & Rituels
                                </button>
                            </h2>
                            <div id="service2" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                                <div class="accordion-body">
                                    <p>Cérémonies traditionnelles pour protection, prospérité et harmonie.</p>
                                    <ul>
                                        <li>Prière de protection - 500 FCFA</li>
                                        <li>Rituel de prospérité - 500 FCFA</li>
                                        <li>Cérémonie familiale - 500 FCFA</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service3">
                                    <i class="fas fa-leaf text-success me-2"></i>
                                    Soins Naturels
                                </button>
                            </h2>
                            <div id="service3" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                                <div class="accordion-body">
                                    <p>Remèdes naturels et plantes médicinales pour divers maux.</p>
                                    <ul>
                                        <li>Traitement naturel simple - 8 000 FCFA</li>
                                        <li>Traitement complet - 15 000 FCFA</li>
                                        <li>Suivi thérapeutique (3 séances) - 35 000 FCFA</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment Form -->
                <div class="appointment-form-card p-4">
                    <h3 class="mb-4">Prendre Rendez-vous</h3>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('consultations.store') }}" method="POST" id="appointmentForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom Complet *</label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Téléphone *</label>
                                <input type="tel" name="phone" class="form-control" required value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email (optionnel)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type de Consultation *</label>
                            <select name="consultation_type" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="traditional" {{ old('consultation_type') == 'traditional' ? 'selected' : '' }}>
                                    Consultation Traditionnelle
                                </option>
                                <option value="prayer" {{ old('consultation_type') == 'prayer' ? 'selected' : '' }}>
                                    Prière et Rituels
                                </option>
                                <option value="natural_care" {{ old('consultation_type') == 'natural_care' ? 'selected' : '' }}>
                                    Soins Naturels
                                </option>
                                <option value="other" {{ old('consultation_type') == 'other' ? 'selected' : '' }}>
                                    Autre
                                </option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date Préférée *</label>
                                <input type="date" name="preferred_date" class="form-control" required
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       value="{{ old('preferred_date') }}"
                                       id="appointmentDate">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Heure Préférée *</label>
                                <select name="preferred_time" class="form-select" required id="appointmentTime">
                                    <option value="">-- Sélectionner --</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message (optionnel)</label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Décrivez brièvement votre situation...">{{ old('message') }}</textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les <a href="#">conditions générales</a> et la <a href="#">politique de confidentialité</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-calendar-check"></i> Confirmer le Rendez-vous
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-card p-4 mb-4">
                    <h5 class="mb-3">Horaires d'Ouverture</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><strong>Lundi - Jeudi:</strong> 9h00 - 18h00</li>
                        <li class="mb-2"><strong>Samedi:</strong> 9h00 - 18h00</li>
                        <li class="mb-2"><strong>Dimanche:</strong> Fermé</li>
                    </ul>
                </div>

                <div class="sidebar-card p-4 mb-4 bg-success text-white">
                    <h5 class="mb-3">Contact Rapide</h5>
                    <p class="mb-3">Besoin d'une consultation urgente ?</p>
                    <a href="tel:{{ env('WHATSAPP_NUMBER') }}" class="btn btn-light w-100 mb-2">
                        <i class="fas fa-phone"></i> Appeler Maintenant
                    </a>
                    <a href="https://wa.me/{{ str_replace('+', '', env('WHATSAPP_NUMBER')) }}" class="btn btn-outline-light w-100">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>

                <div class="sidebar-card p-4">
                    <h5 class="mb-3">Localisation</h5>
                    <p><i class="fas fa-map-marker-alt text-success"></i> Komsilga, Burkina Faso</p>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d123456.789!2d-1.5!3d12.3!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTLCsDE4JzAwLjAiTiAxwrAzMCcwMC4wIlc!5e0!3m2!1sfr!2sbf!4v1234567890"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('appointmentDate');
    const timeSelect = document.getElementById('appointmentTime');

    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;

        if (selectedDate) {
            // Fetch available time slots
            fetch('{{ route("consultations.check-availability") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ date: selectedDate })
            })
            .then(response => response.json())
            .then(data => {
                timeSelect.innerHTML = '<option value="">-- Sélectionner --</option>';
                data.available_slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot;
                    option.textContent = slot;
                    timeSelect.appendChild(option);
                });
            });
        }
    });
});
</script>
@endpush
