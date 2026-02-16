@extends('layouts.admin')

@section('title', 'Détails Rendez-vous')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Détails du Rendez-vous</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.appointments.index') }}">Rendez-vous</a></li>
                    <li class="breadcrumb-item active">Détails</li>
                </ol>
            </nav>
        </div>
        <div>
            @can('appointments.edit')
            <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            @endcan
        </div>
    </div>

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Informations générales -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations Générales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="fas fa-user"></i> Client</h6>
                            <p class="mb-0 h5">{{ $appointment->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="fas fa-flag"></i> Statut</h6>
                            <span class="badge bg-{{ $appointment->status_badge }} fs-6 px-3 py-2">
                                {{ $appointment->status_label }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="far fa-calendar"></i> Date et heure</h6>
                            <p class="mb-1">
                                <strong>{{ $appointment->event->start_datetime->format('d/m/Y') }}</strong>
                            </p>
                            <p class="mb-0">
                                <i class="far fa-clock"></i> {{ $appointment->event->start_datetime->format('H:i') }} -
                                {{ $appointment->event->end_datetime->format('H:i') }}
                                <span class="badge bg-info ms-2">{{ $appointment->event->duration_formatted }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1"><i class="fas fa-user-tie"></i> Administrateur</h6>
                            <p class="mb-0">{{ $appointment->event->admin->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coordonnées -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-address-card"></i> Coordonnées</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2"><i class="fas fa-phone"></i> Téléphone</h6>
                            <p class="mb-0">
                                <a href="tel:{{ $appointment->phone }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-phone"></i> {{ $appointment->phone }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2"><i class="fas fa-envelope"></i> Email</h6>
                            <p class="mb-0">
                                @if($appointment->email)
                                <a href="mailto:{{ $appointment->email }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-envelope"></i> {{ $appointment->email }}
                                </a>
                                @else
                                <span class="text-muted">Non renseigné</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-12">
                            <h6 class="text-muted mb-2"><i class="fas fa-map-marker-alt"></i> Provenance</h6>
                            <p class="mb-0">
                                <span class="badge bg-secondary fs-6">{{ $appointment->provenance }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document d'identité -->
            @if($appointment->hasDocument())
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-id-card"></i> Document d'Identité</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Type de document</h6>
                            <p class="mb-0"><strong>{{ $appointment->doctype }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Numéro</h6>
                            <p class="mb-0"><strong>{{ $appointment->docnumber }}</strong></p>
                        </div>
                        <div class="col-md-12">
                            <h6 class="text-muted mb-2">Photo du document</h6>
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('storage/' . $appointment->imagedoc) }}"
                                     alt="Document"
                                     class="img-thumbnail"
                                     style="max-height: 300px; cursor: pointer;"
                                     onclick="openImageModal(this.src)">
                                <button class="btn btn-sm btn-primary position-absolute top-0 end-0 m-2"
                                        onclick="openImageModal('{{ asset('storage/' . $appointment->imagedoc) }}')">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Consultation -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-stethoscope"></i> Consultation</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted mb-2">Type de consultation</h6>
                            <span class="badge bg-info fs-6 px-3 py-2">
                                {{ $appointment->consultation_type_label }}
                            </span>
                        </div>

                        @if($appointment->message)
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted mb-2">Message du client</h6>
                            <div class="alert alert-light border">
                                <i class="fas fa-comment-dots text-primary"></i>
                                {{ $appointment->message }}
                            </div>
                        </div>
                        @endif

                        @if($appointment->admin_notes)
                        <div class="col-md-12">
                            <h6 class="text-muted mb-2">Notes administrateur</h6>
                            <div class="alert alert-warning border">
                                <i class="fas fa-sticky-note text-warning"></i>
                                {{ $appointment->admin_notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Paiements -->
            @if($appointment->payments()->exists())
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Paiements</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Méthode</th>
                                    <th>Transaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointment->payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                    <td><strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status == 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                    <td>
                                        @if($payment->transaction_id)
                                        <small class="text-muted">{{ $payment->transaction_id }}</small>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total payé:</th>
                                    <th>{{ number_format($appointment->total_paid, 0, ',', ' ') }} FCFA</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @php
                        $paymentStatus = $appointment->payment_status;
                    @endphp

                    <div class="alert alert-{{ $paymentStatus == 'paid' ? 'success' : 'info' }} mb-0">
                        <i class="fas fa-info-circle"></i>
                        <strong>Statut du paiement:</strong>
                        @if($paymentStatus == 'paid')
                        Paiement complet
                        @elseif($paymentStatus == 'partial')
                        Paiement partiel ({{ number_format($appointment->total_paid, 0, ',', ' ') }} FCFA / {{ number_format($appointment->total_amount, 0, ',', ' ') }} FCFA)
                        @elseif($paymentStatus == 'unpaid')
                        Aucun paiement effectué
                        @else
                        Aucun paiement enregistré
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Paiements</h5>
                </div>
                <div class="card-body text-center py-4">
                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Aucun paiement enregistré pour ce rendez-vous.</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions rapides -->
            @can('appointments.edit')
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($appointment->status == 'pending')
                        <form action="{{ route('admin.appointments.confirm', $appointment) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle"></i> Confirmer le Rendez-vous
                            </button>
                        </form>
                        @endif

                        @if(in_array($appointment->status, ['pending', 'confirmed']))
                        <form action="{{ route('admin.appointments.cancel', $appointment) }}"
                              method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?');">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times-circle"></i> Annuler le Rendez-vous
                            </button>
                        </form>
                        @endif

                        @if($appointment->status == 'confirmed')
                        <form action="{{ route('admin.appointments.complete', $appointment) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-check-double"></i> Marquer Terminé
                            </button>
                        </form>
                        @endif

                        @if($appointment->status == 'cancelled')
                        <div class="alert alert-danger mb-0">
                            <i class="fas fa-ban"></i> Ce rendez-vous est annulé
                        </div>
                        @endif

                        @if($appointment->status == 'completed')
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle"></i> Ce rendez-vous est terminé
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endcan

            <!-- Informations système -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations Système</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">ID du rendez-vous</h6>
                        <p class="mb-0"><code>#{{ $appointment->id }}</code></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">ID de l'événement</h6>
                        <p class="mb-0">
                            <a href="{{ route('admin.events.show', $appointment->event) }}">
                                <code>#{{ $appointment->event->id }}</code>
                            </a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Créé le</h6>
                        <p class="mb-0">
                            {{ $appointment->created_at->format('d/m/Y à H:i') }}<br>
                            <small class="text-muted">{{ $appointment->created_at->diffForHumans() }}</small>
                        </p>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-muted mb-1">Dernière modification</h6>
                        <p class="mb-0">
                            {{ $appointment->updated_at->format('d/m/Y à H:i') }}<br>
                            <small class="text-muted">{{ $appointment->updated_at->diffForHumans() }}</small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Événement lié -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-calendar"></i> Événement Associé</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Titre:</strong> {{ $appointment->event->title }}</p>
                    <p class="mb-2"><strong>Type:</strong>
                        <span class="badge bg-primary">{{ ucfirst($appointment->event->event_type) }}</span>
                    </p>
                    <p class="mb-2"><strong>Statut:</strong>
                        <span class="badge bg-{{ $appointment->event->status == 'scheduled' ? 'info' : ($appointment->event->status == 'completed' ? 'success' : 'danger') }}">
                            {{ ucfirst($appointment->event->status) }}
                        </span>
                    </p>
                    <a href="{{ route('admin.events.show', $appointment->event) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-eye"></i> Voir l'événement
                    </a>
                </div>
            </div>

            <!-- Zone dangereuse -->
            @can('appointments.delete')
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Zone Dangereuse</h5>
                </div>
                <div class="card-body">
                    <p class="small text-danger mb-3">
                        <i class="fas fa-warning"></i> La suppression est définitive et irréversible.
                    </p>
                    <form action="{{ route('admin.appointments.destroy', $appointment) }}"
                          method="POST"
                          onsubmit="return confirm('ATTENTION : Êtes-vous absolument sûr de vouloir supprimer ce rendez-vous ?\n\nCette action est IRRÉVERSIBLE et supprimera :\n- Le rendez-vous\n- L\'événement associé\n- Le document d\'identité\n\nTapez OUI pour confirmer.');">
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

<!-- Modal pour voir l'image en grand -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document d'identité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" class="img-fluid" alt="Document">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }
</script>
@endpush

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
    }

    .img-thumbnail {
        transition: transform 0.3s ease;
    }

    .img-thumbnail:hover {
        transform: scale(1.05);
    }
</style>
@endpush
