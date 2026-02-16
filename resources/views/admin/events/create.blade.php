@extends('layouts.admin')

@section('title', 'Nouvel Événement')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 mb-2">Nouvel Événement</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Événements</a></li>
                <li class="breadcrumb-item active">Nouveau</li>
            </ol>
        </nav>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <strong>Note :</strong> Pour créer un rendez-vous client, utilisez plutôt la section
        <a href="{{ route('admin.appointments.create') }}" class="alert-link">Rendez-vous</a>.
    </div>

    <form action="{{ route('admin.events.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-8">
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
                                    <option value="{{ $admin->id }}" {{ old('admin_id', auth()->id()) == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('admin_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="Ex: Préparation des remèdes">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Type d'événement <span class="text-danger">*</span></label>
                                <select name="event_type" id="event_type" class="form-select @error('event_type') is-invalid @enderror" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="daily_work" {{ old('event_type') == 'daily_work' ? 'selected' : '' }}>Travail quotidien</option>
                                    <option value="meeting" {{ old('event_type') == 'meeting' ? 'selected' : '' }}>Réunion</option>
                                    <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('event_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Les rendez-vous clients se créent via la section "Rendez-vous"</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Disponibilité liée (optionnel)</label>
                                <select name="availability_period_id" class="form-select">
                                    <option value="">Aucune</option>
                                    @foreach($availabilities as $availability)
                                    <option value="{{ $availability->id }}">
                                        {{ $availability->day_name }} - {{ $availability->time_range }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="far fa-clock"></i> Date et Heure</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Date de début <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Heure de début <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', '09:00') }}" required>
                                @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date de fin <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', date('Y-m-d')) }}" required>
                                @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Heure de fin <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', '10:00') }}" required>
                                @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4 bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-lightbulb text-warning"></i> Types d'événements</h6>
                        <ul class="small mb-0">
                            <li><strong>Travail quotidien :</strong> Préparation, rituels, organisation</li>
                            <li><strong>Réunion :</strong> Rencontres professionnelles</li>
                            <li><strong>Autre :</strong> Événements divers</li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Statut</h5>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="status" value="scheduled">
                        <span class="badge bg-info fs-6">Programmé</span>
                        <p class="small text-muted mt-2 mb-0">L'événement sera créé avec le statut "Programmé"</p>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Créer l'Événement
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
