@extends('layouts.admin')

@section('title', 'Nouvelle Disponibilité')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="mb-4">
        <h1 class="h3 mb-2">Nouvelle Disponibilité</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.availabilities.index') }}">Disponibilités</a></li>
                <li class="breadcrumb-item active">Nouvelle</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.availabilities.store') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations de base -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations de Base</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Administrateur <span class="text-danger">*</span></label>
                                <select name="admin_id" class="form-select @error('admin_id') is-invalid @enderror" required>
                                    <option value="">-- Sélectionner --</option>
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
                                <label class="form-label">Jour de la semaine <span class="text-danger">*</span></label>
                                <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="1" {{ old('day_of_week') == '1' ? 'selected' : '' }}>Lundi</option>
                                    <option value="2" {{ old('day_of_week') == '2' ? 'selected' : '' }}>Mardi</option>
                                    <option value="3" {{ old('day_of_week') == '3' ? 'selected' : '' }}>Mercredi</option>
                                    <option value="4" {{ old('day_of_week') == '4' ? 'selected' : '' }}>Jeudi</option>
                                    <option value="5" {{ old('day_of_week') == '5' ? 'selected' : '' }}>Vendredi</option>
                                    <option value="6" {{ old('day_of_week') == '6' ? 'selected' : '' }}>Samedi</option>
                                    <option value="0" {{ old('day_of_week') === '0' ? 'selected' : '' }}>Dimanche</option>
                                </select>
                                @error('day_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Type</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ old('is_recurring', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_recurring">
                                        Récurrent (chaque semaine)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horaires -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="far fa-clock"></i> Horaires</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Heure de début <span class="text-danger">*</span></label>
                                <input type="time"
                                       name="start_time"
                                       class="form-control @error('start_time') is-invalid @enderror"
                                       value="{{ old('start_time', '09:00') }}"
                                       required>
                                @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Heure de fin <span class="text-danger">*</span></label>
                                <input type="time"
                                       name="end_time"
                                       class="form-control @error('end_time') is-invalid @enderror"
                                       value="{{ old('end_time', '18:00') }}"
                                       required>
                                @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Période de validité -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Période de Validité (Optionnel)</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Laissez vide pour une disponibilité sans limite de temps
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Date de début</label>
                                <input type="date"
                                       name="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date') }}">
                                @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date de fin</label>
                                <input type="date"
                                       name="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date') }}">
                                @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statut -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-toggle-on"></i> Statut</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Disponibilité active
                            </label>
                        </div>
                        <small class="text-muted">
                            Les disponibilités inactives ne génèrent pas d'événements
                        </small>
                    </div>
                </div>

                <!-- Conseils -->
                <div class="card shadow-sm mb-4 bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-lightbulb text-warning"></i> Conseils</h6>
                        <ul class="small mb-0">
                            <li>Vérifiez qu'il n'y a pas de chevauchement d'horaires</li>
                            <li>Les créneaux récurrents se répètent chaque semaine</li>
                            <li>Utilisez les dates de validité pour des exceptions</li>
                            <li>Désactivez temporairement au lieu de supprimer</li>
                        </ul>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Créer la Disponibilité
                    </button>
                    <a href="{{ route('admin.availabilities.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
