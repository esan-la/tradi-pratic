@extends('layouts.admin')

@section('title', 'Réservations d\'Hôtel')
@section('page-title', 'Réservations d\'Hôtel')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Réservations</li>
@endsection

@section('page-actions')
@if(Auth::user()->hasPermission('reservations.create'))
<a href="{{ route('admin.hotel-reservations.create') }}" class="btn btn-primary-custom">
    <i class="fas fa-plus me-2"></i>Nouvelle Réservation
</a>
@endif
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-details">
                <h3>{{ \App\Models\HotelReservation::count() }}</h3>
                <p>Total Réservations</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h3>{{ \App\Models\HotelReservation::where('status', 'pending')->count() }}</h3>
                <p>En Attente</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3>{{ \App\Models\HotelReservation::where('status', 'confirmed')->count() }}</h3>
                <p>Confirmées</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-details">
                <h3>{{ number_format(\App\Models\HotelReservation::sum('total_amount'), 0, ',', ' ') }}</h3>
                <p>FCFA (Total)</p>
            </div>
        </div>
    </div>
</div>

<div class="custom-card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Réservations</h5>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.hotel-reservations.index') }}" method="GET" class="row g-2">
                    <div class="col-md-3">
                        <select name="hotel_id" class="form-select form-select-sm">
                            <option value="">Tous les hôtels</option>
                            @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>
                                {{ $hotel->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Tous statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Hôtel</th>
                        <th>Chambre</th>
                        <th>Dates</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td><strong>#{{ $reservation->id }}</strong></td>
                        <td>
                            <strong>{{ $reservation->guest_name }}</strong><br>
                            <small class="text-muted">{{ $reservation->guest_phone }}</small>
                        </td>
                        <td>{{ $reservation->hotel->name }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $reservation->room->room_number }} - {{ ucfirst($reservation->room->room_type) }}
                            </span>
                        </td>
                        <td>
                            <small>
                                Du {{ \Carbon\Carbon::parse($reservation->check_in)->format('d/m/Y') }}<br>
                                Au {{ \Carbon\Carbon::parse($reservation->check_out)->format('d/m/Y') }}<br>
                                <strong>{{ $reservation->total_nights }} nuit(s)</strong>
                            </small>
                        </td>
                        <td><strong>{{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA</strong></td>
                        <td>
                            @if($reservation->status === 'pending')
                                <span class="badge bg-warning">En attente</span>
                            @elseif($reservation->status === 'confirmed')
                                <span class="badge bg-info">Confirmé</span>
                            @elseif($reservation->status === 'cancelled')
                                <span class="badge bg-danger">Annulé</span>
                            @else
                                <span class="badge bg-success">Complété</span>
                            @endif
                        </td>
                        <td>
                            @if($reservation->payment_status === 'paid')
                                <span class="badge bg-success">Payé</span>
                            @elseif($reservation->payment_status === 'refunded')
                                <span class="badge bg-warning">Remboursé</span>
                            @else
                                <span class="badge bg-danger">Non payé</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.hotel-reservations.show', $reservation) }}"
                                   class="btn btn-sm btn-action btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(Auth::user()->hasPermission('reservations.confirm') && $reservation->status === 'pending')
                                <form action="{{ route('admin.hotel-reservations.confirm', $reservation) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-action btn-outline-success" title="Confirmer">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif

                                @if(Auth::user()->hasPermission('reservations.cancel') && in_array($reservation->status, ['pending', 'confirmed']))
                                <form action="{{ route('admin.hotel-reservations.cancel', $reservation) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Annuler cette réservation ?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-action btn-outline-warning" title="Annuler">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif

                                @if(Auth::user()->hasPermission('reservations.delete'))
                                <form action="{{ route('admin.hotel-reservations.destroy', $reservation) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette réservation ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action btn-outline-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3 d-block"></i>
                            Aucune réservation trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($reservations->hasPages())
    <div class="card-footer">
        {{ $reservations->links() }}
    </div>
    @endif
</div>
@endsection
