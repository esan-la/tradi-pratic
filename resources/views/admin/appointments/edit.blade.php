@extends('layouts.admin')

@section('title', 'Modifier Rendez-vous')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="mb-4">
        <h1 class="h3 mb-2">Modifier le Rendez-vous</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.appointments.index') }}">Rendez-vous</a></li>
                <li class="breadcrumb-item active">Modifier</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Date et Heure -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Date et Heure</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Administrateur <span class="text-danger">*</span></label>
                                <select name="admin_id" class="form-select @error('admin_id') is-invalid @enderror" required>
                                    @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $appointment->event->admin_id == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('admin_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date et heure début <span class="text-danger">*</span></label>
                                <input type="datetime-local"
                                       name="start_datetime"
                                       class="form-control @error('start_datetime') is-invalid @enderror"
                                       value="{{ old('start_datetime', $appointment->event->start_datetime->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('start_datetime')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date et heure fin <span class="text-danger">*</span></label>
                                <input type="datetime-local"
                                       name="end_datetime"
                                       class="form-control @error('end_datetime') is-invalid @enderror"
                                       value="{{ old('end_datetime', $appointment->event->end_datetime->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('end_datetime')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations Client -->
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
                                       value="{{ old('name', $appointment->name) }}"
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
                                       value="{{ old('email', $appointment->email) }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $appointment->phone) }}"
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
                                       value="{{ old('provenance', $appointment->provenance) }}"
                                       required>
                                @error('provenance')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document d'Identité -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-id-card"></i> Document d'Identité</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Type de document</label>
                                <select name="doctype" class="form-select @error('doctype') is-invalid @enderror">
                                    <option value="">Aucun</option>
                                    <option value="CNI" {{ old('doctype', $appointment->doctype) == 'CNI' ? 'selected' : '' }}>CNI</option>
                                    <option value="Passeport" {{ old('doctype', $appointment->doctype) == 'Passeport' ? 'selected' : '' }}>Passeport</option>
                                    <option value="Permis" {{ old('doctype', $appointment->doctype) == 'Permis' ? 'selected' : '' }}>Permis de conduire</option>
                                    <option value="Autre" {{ old('doctype', $appointment->doctype) == 'Autre' ? 'selected' : '' }}>Autre</option>
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
                                       value="{{ old('docnumber', $appointment->docnumber) }}">
                                @error('docnumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($appointment->imagedoc)
                            <div class="col-md-12">
                                <label class="form-label">Document actuel</label>
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $appointment->imagedoc) }}"
                                         alt="Document"
                                         class="img-thumbnail"
                                         style="max-height: 150px; cursor: pointer;"
                                         onclick="window.open(this.src, '_blank')">
                                </div>
                            </div>
                            @endif

                            <div class="col-md-12">
                                <label class="form-label">
                                    {{ $appointment->imagedoc ? 'Remplacer le document' : 'Ajouter un document' }}
                                </label>
                                <input type="file"
                                       name="imagedoc"
                                       class="form-control @error('imagedoc') is-invalid @enderror"
                                       accept="image/*">
                                <small class="text-muted">
                                    {{ $appointment->imagedoc ? 'Laisser vide pour conserver le document actuel' : 'Formats: JPG, PNG. Max: 5MB' }}
                                </small>
                                @error('imagedoc')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Type de Consultation -->
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
                                    <option value="traditional" {{ old('consultation_type', $appointment->consultation_type) == 'traditional' ? 'selected' : '' }}>Consultation Traditionnelle</option>
                                    <option value="prayer" {{ old('consultation_type', $appointment->consultation_type) == 'prayer' ? 'selected' : '' }}>Prière</option>
                                    <option value="natural_care" {{ old('consultation_type', $appointment->consultation_type) == 'natural_care' ? 'selected' : '' }}>Soin Naturel</option>
                                    <option value="Consultation_spirituelle" {{ old('consultation_type', $appointment->consultation_type) == 'Consultation_spirituelle' ? 'selected' : '' }}>Consultation Spirituelle</option>
                                    <option value="Autres" {{ old('consultation_type', $appointment->consultation_type) == 'Autres' ? 'selected' : '' }}>Autres</option>
                                </select>
                                @error('consultation_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12" id="autre-consultation-group" style="display: {{ old('consultation_type', $appointment->consultation_type) == 'Autres' ? 'block' : 'none' }};">
                                <label class="form-label">Préciser</label>
                                <input type="text"
                                       name="autre_consultation"
                                       class="form-control @error('autre_consultation') is-invalid @enderror"
                                       value="{{ old('autre_consultation', $appointment->autre_consultation) }}">
                                @error('autre_consultation')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Message du client</label>
                                <textarea name="message"
                                          class="form-control @error('message') is-invalid @enderror"
                                          rows="4">{{ old('message', $appointment->message) }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Notes administrateur</label>
                                <textarea name="admin_notes"
                                          class="form-control @error('admin_notes') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Notes internes, observations, etc.">{{ old('admin_notes', $appointment->admin_notes) }}</textarea>
                                @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statut Actuel -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Statut Actuel</h5>
                    </div>
                    <div class="card-body text-center">
                        <span class="badge bg-{{ $appointment->status_badge }} fs-5 px-4 py-2">
                            {{ $appointment->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Informations -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="far fa-calendar-alt"></i> Dates</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted mb-1">Créé le</h6>
                            <p class="mb-0">{{ $appointment->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="mb-0">
                            <h6 class="text-muted mb-1">Modifié le</h6>
                            <p class="mb-0">{{ $appointment->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Historique des modifications -->
                @if($appointment->updated_at != $appointment->created_at)
                <div class="card shadow-sm mb-4 bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-history text-warning"></i> Historique</h6>
                        <p class="small mb-0">
                            Ce rendez-vous a été modifié
                            {{ $appointment->updated_at->diffForHumans() }}.
                        </p>
                    </div>
                </div>
                @endif

                <!-- Boutons d'action -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Afficher/masquer le champ "Autre consultation"
        $('#consultation_type').on('change', function() {
            if ($(this).val() === 'Autres') {
                $('#autre-consultation-group').slideDown();
            } else {
                $('#autre-consultation-group').slideUp();
            }
        });
    });
</script>
@endpush
