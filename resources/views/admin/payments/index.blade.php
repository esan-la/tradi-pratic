@extends('layouts.admin')

@section('title', 'Paiements')
@section('page-title', 'Gestion des Paiements')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Paiements</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Paiements</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['completed'] }}</h3>
                <p>Complétés</p>
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
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-details">
                <h3>{{ number_format($stats['total_amount'], 0, ',', ' ') }}</h3>
                <p>FCFA (Total)</p>
            </div>
        </div>
    </div>
</div>

<div class="custom-card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Historique des Paiements</h5>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.payments.index') }}" method="GET" class="row g-2">
                    <div class="col-md-3">
                        <select name="payment_method" class="form-select form-select-sm">
                            <option value="">Toutes méthodes</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                            <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Virement</option>
                            <option value="cheque" {{ request('payment_method') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Tous statuts</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Remboursé</option>
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
                        <th>Transaction</th>
                        <th>Payeur</th>
                        <th>Méthode</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>
                            <strong>{{ $payment->transaction_id }}</strong>
                        </td>
                        <td>
                            <strong>{{ $payment->payer_name }}</strong><br>
                            <small class="text-muted">
                                @if($payment->payer_email)
                                    <i class="fas fa-envelope me-1"></i>{{ $payment->payer_email }}<br>
                                @endif
                                @if($payment->payer_phone)
                                    <i class="fas fa-phone me-1"></i>{{ $payment->payer_phone }}
                                @endif
                            </small>
                        </td>
                        <td>
                            @if($payment->payment_method === 'cash')
                                <span class="badge bg-success">
                                    <i class="fas fa-money-bill me-1"></i>Espèces
                                </span>
                            @elseif($payment->payment_method === 'mobile_money')
                                <span class="badge bg-primary">
                                    <i class="fas fa-mobile-alt me-1"></i>Mobile Money
                                </span>
                            @elseif($payment->payment_method === 'bank_transfer')
                                <span class="badge bg-info">
                                    <i class="fas fa-university me-1"></i>Virement
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-file-invoice me-1"></i>{{ ucfirst($payment->payment_method) }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <strong class="text-success">
                                {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
                            </strong>
                        </td>
                        <td>
                            @if($payment->status === 'completed')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Complété
                                </span>
                            @elseif($payment->status === 'pending')
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>En attente
                                </span>
                            @elseif($payment->status === 'failed')
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Échoué
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-undo me-1"></i>Remboursé
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($payment->paid_at)
                                {{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') }}
                            @else
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.payments.show', $payment) }}"
                                   class="btn btn-sm btn-action btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-money-bill-wave fa-3x mb-3 d-block"></i>
                            Aucun paiement trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($payments->hasPages())
    <div class="card-footer">
        {{ $payments->links() }}
    </div>
    @endif
</div>

<!-- Summary Card -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="custom-card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Résumé par Méthode de Paiement</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <i class="fas fa-money-bill fa-2x text-success mb-2"></i>
                        <h6>Espèces</h6>
                        <p class="mb-0">
                            <strong>
                                {{ number_format(\App\Models\Payment::where('payment_method', 'cash')->where('status', 'completed')->sum('amount'), 0, ',', ' ') }} FCFA
                            </strong>
                        </p>
                    </div>
                    <div class="col-md-3 text-center">
                        <i class="fas fa-mobile-alt fa-2x text-primary mb-2"></i>
                        <h6>Mobile Money</h6>
                        <p class="mb-0">
                            <strong>
                                {{ number_format(\App\Models\Payment::where('payment_method', 'mobile_money')->where('status', 'completed')->sum('amount'), 0, ',', ' ') }} FCFA
                            </strong>
                        </p>
                    </div>
                    <div class="col-md-3 text-center">
                        <i class="fas fa-university fa-2x text-info mb-2"></i>
                        <h6>Virement Bancaire</h6>
                        <p class="mb-0">
                            <strong>
                                {{ number_format(\App\Models\Payment::where('payment_method', 'bank_transfer')->where('status', 'completed')->sum('amount'), 0, ',', ' ') }} FCFA
                            </strong>
                        </p>
                    </div>
                    <div class="col-md-3 text-center">
                        <i class="fas fa-file-invoice fa-2x text-secondary mb-2"></i>
                        <h6>Chèque</h6>
                        <p class="mb-0">
                            <strong>
                                {{ number_format(\App\Models\Payment::where('payment_method', 'cheque')->where('status', 'completed')->sum('amount'), 0, ',', ' ') }} FCFA
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
