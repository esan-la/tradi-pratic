@extends('layouts.admin')

@section('title', 'Détails du rendez-vous')
@section('page-title', 'Détails du rendez-vous #' . $appointment->id)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informations du rendez-vous</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Nom du client:</strong>
                        <p>{{ $appointment->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Téléphone:</strong>
                        <p><a href="tel:{{ $appointment->phone }}">{{ $appointment->phone }}</a></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong>
                        <p>{{ $appointment->email ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Type de consultation:</strong>
                        <p>{{ $appointment->consultation_type }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Date:</strong>
                        <p>{{ $appointment->preferred_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Heure:</strong>
                        <p>{{ $appointment->preferred_time }}</p>
                    </div>
                    <div class="col-md-12">
                        <strong>Message du client:</strong>
                        <p>{{ $appointment->message ?? 'Aucun message' }}</p>
                    </div>
                    <div class="col-md-12">
                        <strong>Notes administratives:</strong>
                        <p class="text-muted">{{ $appointment->admin_notes ?? 'Aucune note' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Statut</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.appointments.update-status', $appointment) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Changer le statut:</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                            <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Terminé</option>
                            <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note:</label>
                        <textarea name="admin_notes" class="form-control" rows="3">{{ $appointment->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $appointment->phone) }}" class="btn btn-success w-100 mb-2" target="_blank">
                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                </a>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary w-100 mb-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
