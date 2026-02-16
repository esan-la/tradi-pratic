@extends('layouts.admin')

@section('title', 'Gestion des Disponibilités')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Gestion des Disponibilités</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Disponibilités</li>
                </ol>
            </nav>
        </div>
        @if(Auth::user()->hasPermission('availabilities.create'))
        <a href="{{ route('admin.availabilities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Disponibilité
        </a>
        @endif
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.availabilities.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Administrateur</label>
                        <select name="admin_id" class="form-select">
                            <option value="">Tous les admins</option>
                            @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Jour de la semaine</label>
                        <select name="day_of_week" class="form-select">
                            <option value="">Tous les jours</option>
                            <option value="0" {{ request('day_of_week') === '0' ? 'selected' : '' }}>Dimanche</option>
                            <option value="1" {{ request('day_of_week') == '1' ? 'selected' : '' }}>Lundi</option>
                            <option value="2" {{ request('day_of_week') == '2' ? 'selected' : '' }}>Mardi</option>
                            <option value="3" {{ request('day_of_week') == '3' ? 'selected' : '' }}>Mercredi</option>
                            <option value="4" {{ request('day_of_week') == '4' ? 'selected' : '' }}>Jeudi</option>
                            <option value="5" {{ request('day_of_week') == '5' ? 'selected' : '' }}>Vendredi</option>
                            <option value="6" {{ request('day_of_week') == '6' ? 'selected' : '' }}>Samedi</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="is_active" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau groupé par jour -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($availabilities->count() > 0)
                @php
                    $daysOfWeek = [
                        0 => 'Dimanche',
                        1 => 'Lundi',
                        2 => 'Mardi',
                        3 => 'Mercredi',
                        4 => 'Jeudi',
                        5 => 'Vendredi',
                        6 => 'Samedi'
                    ];
                    $groupedByDay = $availabilities->groupBy('day_of_week')->sortKeys();
                @endphp

                @foreach($groupedByDay as $day => $dayAvailabilities)
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-calendar-day text-primary"></i>
                        {{ $daysOfWeek[$day] }}
                        <span class="badge bg-secondary ms-2">{{ $dayAvailabilities->count() }} créneau(x)</span>
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Administrateur</th>
                                    <th>Horaires</th>
                                    <th>Type</th>
                                    <th>Période</th>
                                    <th>Statut</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dayAvailabilities as $availability)
                                <tr>
                                    <td>
                                        <strong>{{ $availability->admin->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info fs-6">
                                            <i class="far fa-clock"></i>
                                            {{ $availability->time_range }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($availability->is_recurring)
                                        <span class="badge bg-primary">
                                            <i class="fas fa-redo"></i> Récurrent
                                        </span>
                                        @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-calendar-day"></i> Ponctuel
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($availability->start_date || $availability->end_date)
                                            <small>
                                                {{ $availability->start_date ? $availability->start_date->format('d/m/Y') : '∞' }}
                                                →
                                                {{ $availability->end_date ? $availability->end_date->format('d/m/Y') : '∞' }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($availability->is_active)
                                        <span class="badge bg-success">Actif</span>
                                        @else
                                        <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('availabilities.view')
                                            <a href="{{ route('admin.availabilities.show', $availability) }}"
                                               class="btn btn-sm btn-info"
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan

                                            @can('availabilities.edit')
                                            <form action="{{ route('admin.availabilities.toggle', $availability) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm {{ $availability->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $availability->is_active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $availability->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>

                                            <a href="{{ route('admin.availabilities.edit', $availability) }}"
                                               class="btn btn-sm btn-primary"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan

                                            @can('availabilities.delete')
                                            <form action="{{ route('admin.availabilities.destroy', $availability) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Supprimer cette disponibilité ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $availabilities->links() }}
                </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucune disponibilité trouvée.</p>
                @can('availabilities.create')
                <a href="{{ route('admin.availabilities.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer la première disponibilité
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
    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-group .btn {
        margin: 0 2px;
    }
</style>
@endpush
