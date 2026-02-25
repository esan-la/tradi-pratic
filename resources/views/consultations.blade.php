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

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#service4">
                                    <i class="fas fa-praying-hands text-success me-2"></i>
                                    Consultation Spirituelle
                                </button>
                            </h2>
                            <div id="service4" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                                <div class="accordion-body">
                                    <p>Accompagnement spirituel et guidance pour votre chemin de vie.</p>
                                    <ul>
                                        <li>Consultation spirituelle (30 minutes) - 1 000 FCFA</li>
                                        <li>Séance d'éveil spirituel (1h) - 2 500 FCFA</li>
                                        <li>Programme spirituel personnalisé - Sur devis</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment Form -->
                <div class="appointment-form-card p-4">
                    <h3 class="mb-4">
                        <i class="fas fa-calendar-check text-success me-2"></i>
                        Prendre Rendez-vous
                    </h3>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Erreurs de validation</h6>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('consultations.store') }}" method="POST" enctype="multipart/form-data" id="appointmentForm">
                        @csrf

                        <!-- Étape 1: Informations Personnelles -->
                        <div class="form-section mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <span class="badge bg-success me-2">1</span>
                                Vos Informations
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom Complet <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           required
                                           value="{{ old('name') }}"
                                           placeholder="Ex: Jean Ouédraogo">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel"
                                           name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           required
                                           value="{{ old('phone') }}"
                                           placeholder="+226 XX XX XX XX">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email (optionnel)</label>
                                    <input type="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}"
                                           placeholder="votre@email.com">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Provenance <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="provenance"
                                           class="form-control @error('provenance') is-invalid @enderror"
                                           required
                                           value="{{ old('provenance') }}"
                                           placeholder="Ex: Ouagadougou, Secteur 30">
                                    @error('provenance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Étape 2: Type de Consultation -->
                        <div class="form-section mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <span class="badge bg-success me-2">2</span>
                                Type de Consultation
                            </h5>

                            <div class="mb-3">
                                <label class="form-label">Choisissez votre consultation <span class="text-danger">*</span></label>
                                <select name="consultation_type"
                                        id="consultation_type"
                                        class="form-select @error('consultation_type') is-invalid @enderror"
                                        required>
                                    <option value="">-- Sélectionner un type --</option>
                                    <option value="traditional" {{ old('consultation_type') == 'traditional' ? 'selected' : '' }}>
                                        Consultation Traditionnelle
                                    </option>
                                    <option value="prayer" {{ old('consultation_type') == 'prayer' ? 'selected' : '' }}>
                                        Prière et Rituels
                                    </option>
                                    <option value="natural_care" {{ old('consultation_type') == 'natural_care' ? 'selected' : '' }}>
                                        Soins Naturels
                                    </option>
                                    <option value="Consultation_spirituelle" {{ old('consultation_type') == 'Consultation_spirituelle' ? 'selected' : '' }}>
                                        Consultation Spirituelle
                                    </option>
                                    <option value="Autres" {{ old('consultation_type') == 'Autres' ? 'selected' : '' }}>
                                        Autre (à préciser)
                                    </option>
                                </select>
                                @error('consultation_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="autre_consultation_group" style="display: none;">
                                <label class="form-label">Précisez le type <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="autre_consultation"
                                       class="form-control @error('autre_consultation') is-invalid @enderror"
                                       value="{{ old('autre_consultation') }}"
                                       placeholder="Décrivez le type de consultation souhaité">
                                @error('autre_consultation')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Décrivez votre situation (optionnel)</label>
                                <textarea name="message"
                                          class="form-control @error('message') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Décrivez brièvement votre situation, vos attentes...">{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Étape 3: Date et Heure -->
                        <div class="form-section mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <span class="badge bg-success me-2">3</span>
                                Date et Heure du Rendez-vous
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date souhaitée <span class="text-danger">*</span></label>
                                    <input type="date"
                                           name="preferred_date"
                                           id="appointmentDate"
                                           class="form-control @error('preferred_date') is-invalid @enderror"
                                           required
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           value="{{ old('preferred_date') }}">
                                    @error('preferred_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> Rendez-vous disponibles du lundi au samedi
                                    </small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Créneau horaire <span class="text-danger">*</span></label>
                                    <select name="preferred_time"
                                            id="appointmentTime"
                                            class="form-select @error('preferred_time') is-invalid @enderror"
                                            required>
                                        <option value="">-- Choisir une date d'abord --</option>
                                    </select>
                                    @error('preferred_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="loading-slots" class="text-center mt-2" style="display: none;">
                                        <div class="spinner-border spinner-border-sm text-success" role="status">
                                            <span class="visually-hidden">Chargement...</span>
                                        </div>
                                        <small class="text-muted ms-2">Chargement des créneaux...</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Conditions -->
                        <div class="form-check mb-4">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="terms"
                                   required>
                            <label class="form-check-label" for="terms">
                                J'accepte les <a href="#" target="_blank">conditions générales</a> et la
                                <a href="#" target="_blank">politique de confidentialité</a> <span class="text-danger">*</span>
                            </label>
                        </div>

                        <!-- Note importante -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Important :</strong> Votre demande sera traitée sous 24h. Vous recevrez une confirmation par téléphone ou SMS.
                        </div>

                        <!-- Bouton Submit -->
                        <button type="submit" class="btn btn-success btn-lg w-100" id="submitBtn">
                            <i class="fas fa-calendar-check"></i> Envoyer ma Demande de Rendez-vous
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Horaires -->
                <div class="sidebar-card p-4 mb-4 shadow-sm">
                    <h5 class="mb-3">
                        <i class="far fa-clock text-success"></i>
                        Horaires d'Ouverture
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2 d-flex justify-content-between">
                            <strong>Lundi - Jeudi:</strong>
                            <span>9h00 - 18h00</span>
                        </li>
                        <li class="mb-2 d-flex justify-content-between">
                            <strong>Vendredi:</strong>
                            <span>9h00 - 18h00</span>
                        </li>
                        <li class="mb-2 d-flex justify-content-between">
                            <strong>Samedi:</strong>
                            <span>9h00 - 18h00</span>
                        </li>
                        <li class="mb-0 d-flex justify-content-between">
                            <strong>Dimanche:</strong>
                            <span class="text-danger">Fermé</span>
                        </li>
                    </ul>
                </div>

                <!-- Contact Rapide -->
                <div class="sidebar-card p-4 mb-4 bg-success text-white shadow-sm">
                    <h5 class="mb-3">
                        <i class="fas fa-phone-volume"></i>
                        Contact Rapide
                    </h5>
                    <p class="mb-3">Besoin d'une consultation urgente ?</p>
                    <div class="d-grid gap-2">
                        <a href="tel:{{ env('WHATSAPP_NUMBER') }}" class="btn btn-light">
                            <i class="fas fa-phone"></i> Appeler Maintenant
                        </a>
                        <a href="https://wa.me/{{ str_replace(['+', ' '], '', env('WHATSAPP_NUMBER')) }}"
                           target="_blank"
                           class="btn btn-outline-light">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="mailto:{{ env('CONTACT_EMAIL') }}" class="btn btn-outline-light">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>

                <!-- Réservation Hôtel -->
                <div class="sidebar-card p-4 mb-4 bg-primary text-white shadow-sm">
                    <h5 class="mb-3">
                        <i class="fas fa-hotel"></i>
                        Hébergement
                    </h5>
                    <p class="mb-3">Venez de loin ? Réservez votre chambre d'hôtel !</p>
                    {{-- <a href="{{ route('hotels.index') }}" class="btn btn-light w-100"> --}}
                    <a href="#" class="btn btn-light w-100">
                        <i class="fas fa-bed"></i> Réserver un Hôtel
                    </a>
                </div>

                <!-- Localisation -->
                <div class="sidebar-card p-4 shadow-sm">
                    <h5 class="mb-3">
                        <i class="fas fa-map-marker-alt text-success"></i>
                        Notre Localisation
                    </h5>
                    <p class="mb-3">
                        <strong>Adresse :</strong><br>
                        Komsilga, Burkina Faso
                    </p>

                    <!-- Map -->
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe
                            src="https://www.google.com/maps?q=12.077072,-1.700181&z=15&output=embed"
                            style="border:0; border-radius: 8px;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <a href="https://www.google.com/maps/dir/?api=1&destination=12.077072,-1.700181"
                    target="_blank"
                    class="btn btn-outline-success w-100">
                        <i class="fas fa-directions"></i> Obtenir l'Itinéraire
                    </a>
                </div>


            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .appointment-form-card,
    .sidebar-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #2d6a4f;
    }

    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #2d6a4f;
    }

    .badge {
        font-size: 0.9rem;
    }

    .sidebar-card {
        transition: transform 0.3s ease;
    }

    .sidebar-card:hover {
        transform: translateY(-5px);
    }

    #submitBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const consultationType = document.getElementById('consultation_type');
    const autreGroup = document.getElementById('autre_consultation_group');
    const dateInput = document.getElementById('appointmentDate');
    const timeSelect = document.getElementById('appointmentTime');
    const loadingSlots = document.getElementById('loading-slots');
    const submitBtn = document.getElementById('submitBtn');

    // Afficher/masquer le champ "Autre"
    consultationType.addEventListener('change', function() {
        if (this.value === 'Autres') {
            autreGroup.style.display = 'block';
        } else {
            autreGroup.style.display = 'none';
        }
    });

    // Déclencher au chargement si "Autres" est sélectionné
    if (consultationType.value === 'Autres') {
        autreGroup.style.display = 'block';
    }

    // Charger les créneaux disponibles
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;

        if (selectedDate) {
            loadingSlots.style.display = 'block';
            timeSelect.disabled = true;
            timeSelect.innerHTML = '<option value="">Chargement...</option>';

            // Appel AJAX pour récupérer les créneaux
            fetch('{{ route("consultations.check-availability") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ date: selectedDate })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                loadingSlots.style.display = 'none';
                timeSelect.disabled = false;
                timeSelect.innerHTML = '<option value="">-- Sélectionner un créneau --</option>';

                if (data.available_slots && data.available_slots.length > 0) {
                    data.available_slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.start + '-' + slot.end;
                        option.textContent = slot.start + ' - ' + slot.end;
                        timeSelect.appendChild(option);
                    });
                } else {
                    timeSelect.innerHTML = '<option value="">Aucun créneau disponible</option>';
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                loadingSlots.style.display = 'none';
                timeSelect.disabled = false;
                timeSelect.innerHTML = '<option value="">Erreur de chargement</option>';

                // Afficher un message d'erreur
                alert('Impossible de charger les créneaux disponibles. Veuillez réessayer.');
            });
        } else {
            timeSelect.innerHTML = '<option value="">-- Choisir une date d\'abord --</option>';
            timeSelect.disabled = false;
        }
    });

    // Validation du formulaire
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi en cours...';
    });
});
</script>
@endpush
