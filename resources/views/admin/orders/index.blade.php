@extends('layouts.admin')

@section('title', 'Commandes')
@section('page-title', 'Gestion des Commandes')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Commandes</li>
@endsection

@section('page-actions')
@if(Auth::user()->hasPermission('orders.create'))
<a href="{{ route('admin.orders.create') }}" class="btn btn-primary-custom">
    <i class="fas fa-plus me-2"></i>Nouvelle Commande
</a>
@endif
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Commandes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['pending'] }}</h3>
                <p>En Attente</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-truck"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['shipped'] }}</h3>
                <p>Expédiées</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['paid'] }}</h3>
                <p>Payées</p>
            </div>
        </div>
    </div>
</div>

<div class="custom-card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Commandes</h5>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Tous statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payée</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>
                    <div class="col-md-6">
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
                        <th>Commande</th>
                        <th>Client</th>
                        <th>Articles</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>
                            <strong>{{ $order->customer_name }}</strong><br>
                            <small class="text-muted">
                                @if($order->customer_email)
                                    <i class="fas fa-envelope me-1"></i>{{ $order->customer_email }}<br>
                                @endif
                                <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $order->items_count }} article(s)</span>
                        </td>
                        <td><strong>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</strong></td>
                        <td>
                            @if($order->status === 'pending')
                                <span class="badge bg-warning">En attente</span>
                            @elseif($order->status === 'paid')
                                <span class="badge bg-success">Payée</span>
                            @elseif($order->status === 'shipped')
                                <span class="badge bg-info">Expédiée</span>
                            @else
                                <span class="badge bg-danger">Annulée</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="btn btn-sm btn-action btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(Auth::user()->hasPermission('orders.delete'))
                                <form action="{{ route('admin.orders.destroy', $order) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette commande ?');">
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
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-shopping-cart fa-3x mb-3 d-block"></i>
                            Aucune commande trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
    <div class="card-footer">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
