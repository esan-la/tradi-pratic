@extends('layouts.admin')

@section('title', 'Modifier Événement')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 mb-2">Modifier l'Événement</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Événements</a></li>
                <li class="breadcrumb-item active">Modifier</li>
            </ol>
        </nav>
    </div>

    @if($event->event_type === 'appointment')
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Attention :</strong> Cet événement est un rendez-vous client.
        Pour le modifier, utilisez plutôt la section
        <a href="{{ route('admin.appointments.edit', $event->appointment) }}" class="alert-link">Rendez-vous</a>.
    </div>
    @endif

    <form action="{{ route('admin.events.update', $event) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations de Base</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Administrateur</label>
                                <select name="admin_id" class="form-select @error('admin_id') is-invalid @enderror" required {{ $event->event_type === 'appointment' ? 'disabled' : '' }}>
                                    @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $event->admin_id == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('admin_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Titre</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $event->title) }}" required {{ $event->event_type === 'appointment' ? 'readonly' : '' }}>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Type d'événement</label>
                                <select name="event_type" class="form-select" disabled>
                                    <option value="appointment" {{ $event->event_type == 'appointment' ? 'selected' : '' }}>Rendez-vous</option>
                                    <option value="daily_work" {{ $event->event_type == 'daily_work' ? 'selected' : '' }}>Travail quotidien</option>
                                    <option value="meeting" {{ $event->event_type == 'meeting' ? 'selected' : '' }}>Réunion</option>
                                    <option value="other" {{ $event->event_type == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                                <input type="hidden" name="event_type" value="{{ $event->event_type }}">
                                <small class="text-muted">Le type ne peut pas être modifié</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Disponibilité liée</label>
                                <select name="availability_period_id" class="form-select" {{ $event->event_type === 'appointment' ? 'disabled' : '' }}>
                                    <option value="">Aucune</option>
                                    @foreach($availabilities as $availability)
                                    <option value="{{ $availability->id }}" {{ $event->availability_period_id == $availability->id ? 'selected' : '' }}>
                                        {{ $availability->day_name }} - {{ $availability->time_range }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" {{ $event->event_type === 'appointment' ? 'readonly' : '' }}>{{ old('description', $event->description) }}</textarea>
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
                                <label class="form-label">Date de début</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $event->start_datetime->format('Y-m-d') }}" required {{ $event->event_type === 'appointment' ? 'readonly' : '' }}>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Heure de début</label>
                                <input type="time" name="start_time" class="form-control" value="{{ $event->start_datetime->format('H:i') }}" required {{ $event->event_type === 'appointment' ? 'readonly' : '' }}>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date de fin</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $event->end_datetime->format('Y-m-d') }}" required {{ $event->event_type === 'appointment' ? 'readonly' : '' }}>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Heure de fin</label>
                                <input type="time" name="end_time" class="form-control" value="{{ $event->end_datetime->format('H:i') }}" required {{ $event->event_type === 'appointment' ? 'readonly' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Statut Actuel</h5>
                    </div>
                    <div class="card-body text-center">
                        @php
                            $statusColors = [
                                'scheduled' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$event->status] ?? 'secondary' }} fs-5 px-4 py-2">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                </div>

                @if($event->appointment)
                <div class="card shadow-sm mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Rendez-vous Lié</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Client :</strong> {{ $event->appointment->name }}</p>
                        <p><strong>Téléphone :</strong> {{ $event->appointment->phone }}</p>
                        <a href="{{ route('admin.appointments.show', $event->appointment) }}" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-eye"></i> Voir le rendez-vous
                        </a>
                    </div>
                </div>
                @endif

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informations</h5>
                    </div>
                    <div class="card-body">
                        <p class="small mb-2"><strong>Créé le :</strong><br>{{ $event->created_at->format('d/m/Y à H:i') }}</p>
                        <p class="small mb-0"><strong>Modifié le :</strong><br>{{ $event->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    @if($event->event_type !== 'appointment')
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    @endif
                    <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
