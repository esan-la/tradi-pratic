@extends('layouts.admin')

@section('title', 'Dons')
@section('page-title', 'Gestion des Dons')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Dons</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Dons</p>
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
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['received'] }}</h3>
                <p>Reçus</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-details">
                <h3>{{ number_format($stats['total_amount'], 0, ',', ' ') }}</h3>
                <p>FCFA Reçus</p>
            </div>
        </div>
    </div>
</div>

<div class="custom-card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Dons</h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>Don</th>
                        <th>Donateur</th>
                        <th>Type</th>
                        <th>Montant/Détails</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                    <tr>
                        <td><strong>#{{ $donation->id }}</strong></td>
                        <td>{{ $donation->donor->name ?? 'Anonyme' }}</td>
                        <td>
                            @if($donation->type === 'money')
                                <span class="badge bg-success">Argent</span>
                            @elseif($donation->type === 'cheque')
                                <span class="badge bg-info">Chèque</span>
                            @elseif($donation->type === 'object')
                                <span class="badge bg-primary">Objet</span>
                            @else
                                <span class="badge bg-warning">Colis</span>
                            @endif
                        </td>
                        <td>
                            @if(in_array($donation->type, ['money', 'cheque']))
                                <strong>{{ number_format($donation->amount, 0, ',', ' ') }} {{ $donation->currency }}</strong>
                            @else
                                {{ $donation->items->count() }} article(s)
                            @endif
                        </td>
                        <td>
                            @if($donation->status === 'pending')
                                <span class="badge bg-warning">En attente</span>
                            @elseif($donation->status === 'received')
                                <span class="badge bg-success">Reçu</span>
                            @else
                                <span class="badge bg-danger">Annulé</span>
                            @endif
                        </td>
                        <td>{{ $donation->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.donations.show', $donation) }}"
                                   class="btn btn-sm btn-action btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(Auth::user()->hasPermission('donations.receive') && $donation->status === 'pending')
                                <form action="{{ route('admin.donations.receive', $donation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-action btn-outline-success">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-heart fa-3x mb-3 d-block"></i>
                            Aucun don trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($donations->hasPages())
    <div class="card-footer">
        {{ $donations->links() }}
    </div>
    @endif
</div>
@endsection
