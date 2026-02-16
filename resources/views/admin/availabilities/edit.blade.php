@extends('layouts.admin')

@section('title', 'Modifier Disponibilité')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="mb-4">
        <h1 class="h3 mb-2">Modifier la Disponibilité</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.availabilities.index') }}">Disponibilités</a></li>
                <li class="breadcrumb-item active">Modifier</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.availabilities.update', $availability) }}" method="POST">
        @csrf
        @method('PUT')

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
                                    @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $availability->admin_id == $admin->id ? 'selected' : '' }}>
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
                                    <option value="1" {{ $availability->day_of_week == 1 ? 'selected' : '' }}>Lundi</option>
                                    <option value="2" {{ $availability->day_of_week == 2 ? 'selected' : '' }}>Mardi</option>
                                    <option value="3" {{ $availability->day_of_week == 3 ? 'selected' : '' }}>Mercredi</option>
                                    <option value="4" {{ $availability->day_of_week == 4 ? 'selected' : '' }}>Jeudi</option>
                                    <option value="5" {{ $availability->day_of_week == 5 ? 'selected' : '' }}>Vendredi</option>
                                    <option value="6" {{ $availability->day_of_week == 6 ? 'selected' : '' }}>Samedi</option>
                                    <option value="0" {{ $availability->day_of_week == 0 ? 'selected' : '' }}>Dimanche</option>
                                </select>
                                @error('day_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Type</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ $availability->is_recurring ? 'checked' : '' }}>
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
                                       value="{{ old('start_time', substr($availability->start_time, 0, 5)) }}"
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
                                       value="{{ old('end_time', substr($availability->end_time, 0, 5)) }}"
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
                                       value="{{ old('start_date', $availability->start_date?->format('Y-m-d')) }}">
                                @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date de fin</label>
                                <input type="date"
                                       name="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', $availability->end_date?->format('Y-m-d')) }}">
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
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $availability->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Disponibilité active
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Les disponibilités inactives ne génèrent pas d'événements
                        </small>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">Événements liés</small>
                            <h5 class="mb-0">{{ $availability->events()->count() }}</h5>
                        </div>
                        <hr>
                        <div class="mb-0">
                            <small class="text-muted">Créée le</small>
                            <p class="mb-0">{{ $availability->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.availabilities.show', $availability) }}" class="btn btn-secondary">
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
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>
@endpush
