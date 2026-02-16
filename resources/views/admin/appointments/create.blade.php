@extends('layouts.admin')

@section('title', 'Nouveau Rendez-vous')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="mb-4">
        <h1 class="h3 mb-2">Nouveau Rendez-vous</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.appointments.index') }}">Rendez-vous</a></li>
                <li class="breadcrumb-item active">Nouveau</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.appointments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations de l'événement -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Date et Heure</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Administrateur <span class="text-danger">*</span></label>
                                <select name="admin_id" id="admin_id" class="form-select @error('admin_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un administrateur</option>
                                    @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('admin_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="date"
                                       id="date"
                                       class="form-control @error('start_datetime') is-invalid @enderror"
                                       value="{{ old('date') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('start_datetime')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12" id="slots-container" style="display: none;">
                                <label class="form-label">Créneaux disponibles <span class="text-danger">*</span></label>
                                <div id="available-slots" class="d-flex flex-wrap gap-2">
                                    <!-- Les créneaux seront chargés ici -->
                                </div>
                                <input type="hidden" name="start_datetime" id="start_datetime">
                                <input type="hidden" name="end_datetime" id="end_datetime">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations client -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Informations Client</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}"
                                       placeholder="+226 XX XX XX XX"
                                       required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Provenance <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="provenance"
                                       class="form-control @error('provenance') is-invalid @enderror"
                                       value="{{ old('provenance') }}"
                                       placeholder="Ville ou quartier"
                                       required>
                                @error('provenance')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document d'identité -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-id-card"></i> Document d'Identité (Optionnel)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Type de document</label>
                                <select name="doctype" class="form-select @error('doctype') is-invalid @enderror">
                                    <option value="">Sélectionner</option>
                                    <option value="CNI" {{ old('doctype') == 'CNI' ? 'selected' : '' }}>CNI</option>
                                    <option value="Passeport" {{ old('doctype') == 'Passeport' ? 'selected' : '' }}>Passeport</option>
                                    <option value="Permis" {{ old('doctype') == 'Permis' ? 'selected' : '' }}>Permis de conduire</option>
                                    <option value="Autre" {{ old('doctype') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('doctype')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Numéro de document</label>
                                <input type="text"
                                       name="docnumber"
                                       class="form-control @error('docnumber') is-invalid @enderror"
                                       value="{{ old('docnumber') }}">
                                @error('docnumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Photo du document</label>
                                <input type="file"
                                       name="imagedoc"
                                       class="form-control @error('imagedoc') is-invalid @enderror"
                                       accept="image/*">
                                <small class="text-muted">Formats acceptés: JPG, PNG. Taille max: 5MB</small>
                                @error('imagedoc')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consultation -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-stethoscope"></i> Type de Consultation</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="consultation_type"
                                        id="consultation_type"
                                        class="form-select @error('consultation_type') is-invalid @enderror"
                                        required>
                                    <option value="">Sélectionner</option>
                                    <option value="traditional" {{ old('consultation_type') == 'traditional' ? 'selected' : '' }}>Consultation Traditionnelle</option>
                                    <option value="prayer" {{ old('consultation_type') == 'prayer' ? 'selected' : '' }}>Prière</option>
                                    <option value="natural_care" {{ old('consultation_type') == 'natural_care' ? 'selected' : '' }}>Soin Naturel</option>
                                    <option value="Consultation_spirituelle" {{ old('consultation_type') == 'Consultation_spirituelle' ? 'selected' : '' }}>Consultation Spirituelle</option>
                                    <option value="Autres" {{ old('consultation_type') == 'Autres' ? 'selected' : '' }}>Autres</option>
                                </select>
                                @error('consultation_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12" id="autre-consultation-group" style="display: none;">
                                <label class="form-label">Préciser</label>
                                <input type="text"
                                       name="autre_consultation"
                                       class="form-control @error('autre_consultation') is-invalid @enderror"
                                       value="{{ old('autre_consultation') }}">
                                @error('autre_consultation')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Message / Notes</label>
                                <textarea name="message"
                                          class="form-control @error('message') is-invalid @enderror"
                                          rows="4"
                                          placeholder="Raison de la consultation, symptômes, etc.">{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Paiement -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-money-bill"></i> Paiement</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Montant (FCFA)</label>
                            <input type="number"
                                   name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{ old('amount') }}"
                                   min="0"
                                   step="100"
                                   placeholder="0">
                            <small class="text-muted">Laisser vide si pas de paiement immédiat</small>
                            @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Conseils -->
                <div class="card shadow-sm mb-4 bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-info-circle text-info"></i> Conseils</h6>
                        <ul class="small mb-0">
                            <li>Vérifiez la disponibilité avant de créer</li>
                            <li>Assurez-vous des coordonnées du client</li>
                            <li>Le document d'identité est optionnel</li>
                            <li>Le paiement peut être ajouté plus tard</li>
                        </ul>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Créer le Rendez-vous
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .slot-btn {
        min-width: 120px;
        transition: all 0.3s ease;
    }

    .slot-btn.active {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Afficher/masquer le champ "Autre consultation"
        $('#consultation_type').on('change', function() {
            if ($(this).val() === 'Autres') {
                $('#autre-consultation-group').show();
            } else {
                $('#autre-consultation-group').hide();
            }
        });

        // Déclencher au chargement si "Autres" est sélectionné
        if ($('#consultation_type').val() === 'Autres') {
            $('#autre-consultation-group').show();
        }

        // Charger les créneaux disponibles
        function loadAvailableSlots() {
            const adminId = $('#admin_id').val();
            const date = $('#date').val();

            if (adminId && date) {
                $('#slots-container').show();
                $('#available-slots').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Chargement des créneaux...</p></div>');

                $.ajax({
                    url: '{{ route("admin.appointments.available-slots") }}',
                    method: 'GET',
                    data: {
                        admin_id: adminId,
                        date: date
                    },
                    success: function(response) {
                        if (response.slots && response.slots.length > 0) {
                            let slotsHtml = '';
                            response.slots.forEach(function(slot) {
                                slotsHtml += `
                                    <button type="button"
                                            class="btn btn-outline-primary slot-btn"
                                            data-start="${response.date} ${slot.start}:00"
                                            data-end="${response.date} ${slot.end}:00">
                                        <i class="far fa-clock"></i> ${slot.start} - ${slot.end}
                                    </button>
                                `;
                            });
                            $('#available-slots').html(slotsHtml);

                            // Gestion du clic sur les créneaux
                            $('.slot-btn').on('click', function() {
                                $('.slot-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
                                $(this).removeClass('btn-outline-primary').addClass('btn-primary active');

                                $('#start_datetime').val($(this).data('start'));
                                $('#end_datetime').val($(this).data('end'));
                            });
                        } else {
                            $('#available-slots').html('<div class="alert alert-warning mb-0"><i class="fas fa-exclamation-triangle"></i> Aucun créneau disponible pour cette date.</div>');
                        }
                    },
                    error: function() {
                        $('#available-slots').html('<div class="alert alert-danger mb-0"><i class="fas fa-times-circle"></i> Erreur lors du chargement des créneaux.</div>');
                    }
                });
            } else {
                $('#slots-container').hide();
            }
        }

        // Événements pour charger les créneaux
        $('#admin_id, #date').on('change', loadAvailableSlots);
    });
</script>
@endpush
