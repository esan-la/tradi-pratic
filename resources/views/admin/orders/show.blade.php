@extends('layouts.admin')

@section('title', 'Commande #' . $order->id)
@section('page-title', 'Détails de la Commande #' . $order->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="custom-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Informations Client</h5>
                <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }} fs-6">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Nom du client</label>
                        <p class="mb-0"><strong>{{ $order->customer_name }}</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">{{ $order->customer_email ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Téléphone</label>
                        <p class="mb-0">{{ $order->customer_phone }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Date de commande</label>
                        <p class="mb-0">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0">Articles Commandés</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix Unitaire</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong>{{ number_format($item->total_price, 0, ',', ' ') }} FCFA</strong></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end"><strong>TOTAL</strong></td>
                                <td><strong class="text-success fs-5">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if(Auth::user()->hasPermission('orders.update-status'))
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Changer le statut</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Payée</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Expédiée</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
                </form>
            </div>
        </div>
        @endif

        @if(Auth::user()->hasPermission('payments.process') && $order->status !== 'paid')
        <div class="custom-card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Enregistrer Paiement</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.payments.order', $order) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Méthode de paiement</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">Espèces</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Virement</option>
                            <option value="cheque">Chèque</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant</label>
                        <input type="number" name="amount" class="form-control"
                               value="{{ $order->total_amount }}" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check me-2"></i>Confirmer Paiement
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
