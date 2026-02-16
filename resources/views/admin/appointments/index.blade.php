@extends('layouts.admin')

@section('title', 'Gestion des Rendez-vous')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Gestion des Rendez-vous</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Rendez-vous</li>
                </ol>
            </nav>
        </div>
        @if(Auth::user()->hasPermission('appointments.create'))
        <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Rendez-vous
        </a>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total</h6>
                            <h3 class="mb-0">{{ $appointments->total() }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">En attente</h6>
                            <h3 class="mb-0">{{ \App\Models\Appointment::pending()->count() }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Confirmés</h6>
                            <h3 class="mb-0">{{ \App\Models\Appointment::confirmed()->count() }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Terminés</h6>
                            <h3 class="mb-0">{{ \App\Models\Appointment::completed()->count() }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-check-double fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.appointments.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Type de consultation</label>
                        <select name="consultation_type" class="form-select">
                            <option value="">Tous les types</option>
                            <option value="traditional" {{ request('consultation_type') == 'traditional' ? 'selected' : '' }}>Traditionnelle</option>
                            <option value="prayer" {{ request('consultation_type') == 'prayer' ? 'selected' : '' }}>Prière</option>
                            <option value="natural_care" {{ request('consultation_type') == 'natural_care' ? 'selected' : '' }}>Soin Naturel</option>
                            <option value="Consultation_spirituelle" {{ request('consultation_type') == 'Consultation_spirituelle' ? 'selected' : '' }}>Consultation Spirituelle</option>
                            <option value="Autres" {{ request('consultation_type') == 'Autres' ? 'selected' : '' }}>Autres</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Recherche</label>
                        <input type="text" name="search" class="form-control" placeholder="Nom, téléphone..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-1">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($appointments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Date & Heure</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Administrateur</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>
                                <strong>{{ $appointment->name }}</strong><br>
                                <small class="text-muted">
                                    <i class="fas fa-phone"></i> {{ $appointment->phone }}
                                </small>
                            </td>
                            <td>
                                <i class="far fa-calendar"></i> {{ $appointment->event->start_datetime->format('d/m/Y') }}<br>
                                <small class="text-muted">
                                    <i class="far fa-clock"></i> {{ $appointment->event->start_datetime->format('H:i') }} - {{ $appointment->event->end_datetime->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $appointment->consultation_type_label }}</span>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'confirmed' => 'info',
                                        'cancelled' => 'danger',
                                        'completed' => 'success',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                    {{ $appointment->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($appointment->payments()->exists())
                                    @php
                                        $paymentStatus = $appointment->payment_status;
                                        $paymentColors = [
                                            'paid' => 'success',
                                            'unpaid' => 'warning',
                                            'partial' => 'info',
                                            'no_payment' => 'secondary',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $paymentColors[$paymentStatus] ?? 'secondary' }}">
                                        {{ ucfirst($paymentStatus) }}
                                    </span><br>
                                    <small class="text-muted">{{ number_format($appointment->total_paid, 0, ',', ' ') }} FCFA</small>
                                @else
                                    <span class="badge bg-secondary">Aucun</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $appointment->event->admin->name }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    {{-- @can('appointments.view') --}}
                                    <a href="{{ route('admin.appointments.show', $appointment) }}"
                                       class="btn btn-sm btn-info"
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    {{-- @endcan --}}

                                    {{-- @can('appointments.edit') --}}
                                    @if(Auth::user()->hasPermission('appointments.edit'))
                                    @if($appointment->status == 'pending')
                                    <form action="{{ route('admin.appointments.confirm', $appointment) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-success"
                                                title="Confirmer">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <a href="{{ route('admin.appointments.edit', $appointment) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif

                                    {{-- @can('appointments.delete') --}}
                                    @if(Auth::user()->hasPermission('appointments.delete'))
                                    <form action="{{ route('admin.appointments.destroy', $appointment) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?');">
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
                {{ $appointments->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucun rendez-vous trouvé.</p>
                @can('appointments.create')
                <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer le premier rendez-vous
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
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

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
