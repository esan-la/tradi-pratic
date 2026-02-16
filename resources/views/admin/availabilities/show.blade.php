@extends('layouts.admin')

@section('title', 'Détails Disponibilité')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Détails de la Disponibilité</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.availabilities.index') }}">Disponibilités</a></li>
                    <li class="breadcrumb-item active">Détails</li>
                </ol>
            </nav>
        </div>
        @can('availabilities.edit')
        <a href="{{ route('admin.availabilities.edit', $availability) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        @endcan
    </div>

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Informations principales -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations Principales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="fas fa-user-tie"></i> Administrateur</h6>
                            <p class="mb-0 h5">{{ $availability->admin->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="fas fa-calendar-day"></i> Jour de la semaine</h6>
                            <p class="mb-0">
                                <span class="badge bg-info fs-6">{{ $availability->day_name }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="far fa-clock"></i> Horaires</h6>
                            <p class="mb-0">
                                <span class="badge bg-success fs-6">
                                    {{ $availability->time_range }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="fas fa-redo"></i> Type</h6>
                            @if($availability->is_recurring)
                            <span class="badge bg-primary fs-6">
                                <i class="fas fa-redo"></i> Récurrent
                            </span>
                            @else
                            <span class="badge bg-warning fs-6">
                                <i class="fas fa-calendar-day"></i> Ponctuel
                            </span>
                            @endif
                        </div>
                        <div class="col-md-12 mb-0">
                            <h6 class="text-muted mb-1"><i class="fas fa-calendar-alt"></i> Période de validité</h6>
                            @if($availability->start_date || $availability->end_date)
                            <p class="mb-0">
                                Du <strong>{{ $availability->start_date ? $availability->start_date->format('d/m/Y') : '∞' }}</strong>
                                au <strong>{{ $availability->end_date ? $availability->end_date->format('d/m/Y') : '∞' }}</strong>
                            </p>
                            @else
                            <p class="mb-0 text-muted">Sans limite de temps</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Événements liés -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar"></i> Événements Liés
                        <span class="badge bg-primary ms-2">{{ $availability->events()->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($availability->events()->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availability->events()->orderBy('start_datetime', 'desc')->limit(10)->get() as $event)
                                <tr>
                                    <td>
                                        <i class="far fa-calendar"></i> {{ $event->start_datetime->format('d/m/Y') }}<br>
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i> {{ $event->start_datetime->format('H:i') }} - {{ $event->end_datetime->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>{{ $event->title }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($event->event_type) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$event->status] ?? 'secondary' }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @can('events.view')
                                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($availability->events()->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.events.index', ['availability_id' => $availability->id]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-list"></i> Voir tous les événements ({{ $availability->events()->count() }})
                        </a>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Aucun événement lié à cette disponibilité</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions rapides -->
            @can('availabilities.edit')
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.availabilities.toggle', $availability) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $availability->is_active ? 'btn-warning' : 'btn-success' }} w-100">
                                <i class="fas fa-{{ $availability->is_active ? 'pause' : 'play' }}"></i>
                                {{ $availability->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Statut actuel -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Statut Actuel</h5>
                </div>
                <div class="card-body text-center">
                    @if($availability->is_active)
                    <span class="badge bg-success fs-5 px-4 py-2">
                        <i class="fas fa-check-circle"></i> Actif
                    </span>
                    <p class="text-muted mt-2 mb-0 small">
                        Cette disponibilité génère des créneaux
                    </p>
                    @else
                    <span class="badge bg-danger fs-5 px-4 py-2">
                        <i class="fas fa-times-circle"></i> Inactif
                    </span>
                    <p class="text-muted mt-2 mb-0 small">
                        Cette disponibilité est désactivée
                    </p>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total événements</small>
                        <h4 class="mb-0">{{ $availability->events()->count() }}</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Événements à venir</small>
                        <h4 class="mb-0">{{ $availability->events()->where('start_datetime', '>', now())->count() }}</h4>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Durée totale</small>
                        <h4 class="mb-0">
                            {{ \Carbon\Carbon::parse($availability->start_time)->diffInHours(\Carbon\Carbon::parse($availability->end_time)) }}h
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-database"></i> Informations Système</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">ID</small>
                        <p class="mb-0"><code>#{{ $availability->id }}</code></p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Créé le</small>
                        <p class="mb-0">{{ $availability->created_at->format('d/m/Y à H:i') }}</p>
                        <small class="text-muted">{{ $availability->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Modifié le</small>
                        <p class="mb-0">{{ $availability->updated_at->format('d/m/Y à H:i') }}</p>
                        <small class="text-muted">{{ $availability->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            <!-- Zone dangereuse -->
            @can('availabilities.delete')
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Zone Dangereuse</h5>
                </div>
                <div class="card-body">
                    <p class="small text-danger mb-3">
                        <i class="fas fa-warning"></i> La suppression est définitive et irréversible.
                    </p>
                    @if($availability->events()->count() > 0)
                    <div class="alert alert-warning small">
                        <i class="fas fa-info-circle"></i>
                        <strong>Attention :</strong> {{ $availability->events()->count() }} événement(s) sont liés à cette disponibilité.
                    </div>
                    @endif
                    <form action="{{ route('admin.availabilities.destroy', $availability) }}"
                          method="POST"
                          onsubmit="return confirm('ATTENTION : Êtes-vous sûr de vouloir supprimer cette disponibilité ?\n\nCette action est IRRÉVERSIBLE.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash-alt"></i> Supprimer Définitivement
                        </button>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
