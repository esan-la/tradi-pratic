@extends('layouts.admin')

@section('title', 'Gestion des Événements')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Gestion des Événements</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Événements</li>
                </ol>
            </nav>
        </div>
        <div>
            @if(Auth::user()->hasPermission('events.view'))
            <a href="{{ route('admin.events.calendar') }}" class="btn btn-info me-2">
                <i class="fas fa-calendar-alt"></i> Vue Calendrier
            </a>
            @endif
            @if(Auth::user()->hasPermission('events.create'))
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvel Événement
            </a>
            @endif
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-left-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $totalEvents }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Programmés</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $scheduledEvents }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-left-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Terminés</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $completedEvents }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-left-warning">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Aujourd'hui</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $todayEvents }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.events.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Type d'événement</label>
                        <select name="event_type" class="form-select">
                            <option value="">Tous les types</option>
                            <option value="appointment" {{ request('event_type') == 'appointment' ? 'selected' : '' }}>Rendez-vous</option>
                            <option value="daily_work" {{ request('event_type') == 'daily_work' ? 'selected' : '' }}>Travail quotidien</option>
                            <option value="meeting" {{ request('event_type') == 'meeting' ? 'selected' : '' }}>Réunion</option>
                            <option value="other" {{ request('event_type') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Programmé</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Administrateur</label>
                        <select name="admin_id" class="form-select">
                            <option value="">Tous</option>
                            @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Recherche</label>
                        <input type="text" name="search" class="form-control" placeholder="Titre..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des événements -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($events->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Heure</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Administrateur</th>
                            <th>Statut</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td>
                                <div>
                                    <i class="far fa-calendar"></i>
                                    <strong>{{ $event->start_datetime->format('d/m/Y') }}</strong>
                                </div>
                                <small class="text-muted">
                                    <i class="far fa-clock"></i>
                                    {{ $event->start_datetime->format('H:i') }} - {{ $event->end_datetime->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                <strong>{{ $event->title }}</strong>
                                @if($event->description)
                                <br><small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
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
                                <span class="badge bg-{{ $typeColors[$event->event_type] ?? 'secondary' }}">
                                    <i class="fas fa-{{ $typeIcons[$event->event_type] ?? 'calendar' }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                                </span>
                            </td>
                            <td>{{ $event->admin->name }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'scheduled' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$event->status] ?? 'secondary' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if(Auth::user()->hasPermission('events.view'))
                                    <a href="{{ route('admin.events.show', $event) }}"
                                       class="btn btn-sm btn-info"
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif

                                    @if(Auth::user()->hasPermission('events.edit'))
                                    @if($event->event_type !== 'appointment')
                                    <a href="{{ route('admin.events.edit', $event) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif

                                    @if($event->status === 'scheduled')
                                    <form action="{{ route('admin.events.complete', $event) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-success"
                                                title="Terminer">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.events.cancel', $event) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-warning"
                                                title="Annuler"
                                                onclick="return confirm('Annuler cet événement ?');">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @endif

                                    @if(Auth::user()->hasPermission('events.delete'))
                                    <form action="{{ route('admin.events.destroy', $event) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cet événement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $events->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucun événement trouvé.</p>
                @can('events.create')
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer le premier événement
                </a>
                @endcan
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 4px solid #4e73df;
    }
    .border-left-success {
        border-left: 4px solid #1cc88a;
    }
    .border-left-info {
        border-left: 4px solid #36b9cc;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e;
    }
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
