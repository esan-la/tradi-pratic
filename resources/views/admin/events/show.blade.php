@extends('layouts.admin')

@section('title', 'Détails Événement')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Détails de l'Événement</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Événements</a></li>
                    <li class="breadcrumb-item active">Détails</li>
                </ol>
            </nav>
        </div>
        @can('events.edit')
        @if($event->event_type !== 'appointment')
        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        @endif
        @endcan
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations Principales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted mb-1"><i class="fas fa-heading"></i> Titre</h6>
                            <h4 class="mb-0">{{ $event->title }}</h4>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="fas fa-user-tie"></i> Administrateur</h6>
                            <p class="mb-0">{{ $event->admin->name }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="fas fa-tag"></i> Type</h6>
                            @php
                                $typeColors = [
                                    'appointment' => 'primary',
                                    'daily_work' => 'success',
                                    'meeting' => 'warning',
                                    'other' => 'secondary'
                                ];
                                $typeIcons = [
                                    'appointment' => 'calendar-check',
                                    'daily_work' => 'briefcase',
                                    'meeting' => 'users',
                                    'other' => 'calendar'
                                ];
                            @endphp
                            <span class="badge bg-{{ $typeColors[$event->event_type] ?? 'secondary' }} fs-6">
                                <i class="fas fa-{{ $typeIcons[$event->event_type] ?? 'calendar' }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                            </span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="far fa-calendar"></i> Date</h6>
                            <p class="mb-0">
                                <strong>{{ $event->start_datetime->format('d/m/Y') }}</strong>
                                @if($event->start_datetime->format('Y-m-d') !== $event->end_datetime->format('Y-m-d'))
                                → <strong>{{ $event->end_datetime->format('d/m/Y') }}</strong>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="far fa-clock"></i> Horaires</h6>
                            <span class="badge bg-success fs-6">
                                {{ $event->start_datetime->format('H:i') }} - {{ $event->end_datetime->format('H:i') }}
                            </span>
                            <span class="badge bg-info ms-2">
                                {{ $event->duration }} min
                            </span>
                        </div>

                        @if($event->description)
                        <div class="col-md-12 mb-0">
                            <h6 class="text-muted mb-1"><i class="fas fa-align-left"></i> Description</h6>
                            <p class="mb-0">{{ $event->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($event->appointment)
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Rendez-vous Lié</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Client</h6>
                            <p class="mb-0"><strong>{{ $event->appointment->name }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Téléphone</h6>
                            <p class="mb-0">{{ $event->appointment->phone }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Type de consultation</h6>
                            <span class="badge bg-info">{{ ucfirst($event->appointment->consultation_type) }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Statut</h6>
                            @php
                                $aptStatusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $aptStatusColors[$event->appointment->status] ?? 'secondary' }}">
                                {{ ucfirst($event->appointment->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.appointments.show', $event->appointment) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Voir le rendez-vous complet
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if($event->availabilityPeriod)
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Disponibilité Associée</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Jour :</strong> {{ $event->availabilityPeriod->day_name }}<br>
                        <strong>Horaires :</strong> {{ $event->availabilityPeriod->time_range }}
                    </p>
                    <a href="{{ route('admin.availabilities.show', $event->availabilityPeriod) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Voir la disponibilité
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            @can('events.edit')
            @if($event->status === 'scheduled')
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.events.complete', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check"></i> Marquer Terminé
                            </button>
                        </form>

                        <form action="{{ route('admin.events.cancel', $event) }}" method="POST" onsubmit="return confirm('Annuler cet événement ?');">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-ban"></i> Annuler
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endcan

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
                        $statusIcons = [
                            'scheduled' => 'clock',
                            'completed' => 'check-circle',
                            'cancelled' => 'times-circle'
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$event->status] ?? 'secondary' }} fs-5 px-4 py-2">
                        <i class="fas fa-{{ $statusIcons[$event->status] ?? 'question' }}"></i>
                        {{ ucfirst($event->status) }}
                    </span>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-database"></i> Informations Système</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">ID</small>
                        <p class="mb-0"><code>#{{ $event->id }}</code></p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Créé le</small>
                        <p class="mb-0">{{ $event->created_at->format('d/m/Y à H:i') }}</p>
                        <small class="text-muted">{{ $event->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Modifié le</small>
                        <p class="mb-0">{{ $event->updated_at->format('d/m/Y à H:i') }}</p>
                        <small class="text-muted">{{ $event->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            @can('events.delete')
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Zone Dangereuse</h5>
                </div>
                <div class="card-body">
                    @if($event->appointment)
                    <div class="alert alert-warning small mb-3">
                        <i class="fas fa-info-circle"></i>
                        Cet événement est lié à un rendez-vous client.
                    </div>
                    @endif
                    <p class="small text-danger mb-3">La suppression est définitive.</p>
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('ATTENTION : Supprimer cet événement ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash-alt"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
