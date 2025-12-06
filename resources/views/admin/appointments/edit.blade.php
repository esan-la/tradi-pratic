@extends('layouts.admin')

@section('title', isset($appointment) ? 'Modifier le rendez-vous' : 'Nouveau rendez-vous')
@section('page-title', isset($appointment) ? 'Modifier le rendez-vous' : 'Nouveau rendez-vous')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">{{ isset($appointment) ? 'Modifier' : 'Créer' }} un rendez-vous</h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($appointment) ? route('admin.appointments.update', $appointment) : route('admin.appointments.store') }}" method="POST">
                    @csrf
                    @if(isset($appointment))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $appointment->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $appointment->phone ?? '') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $appointment->email ?? '') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type de consultation <span class="text-danger">*</span></label>
                            <select name="consultation_type" class="form-select @error('consultation_type') is-invalid @enderror" required>
                                <option value="">Sélectionner...</option>
                                @foreach($consultationTypes as $type)
                                    <option value="{{ $type }}" {{ old('consultation_type', $appointment->consultation_type ?? '') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('consultation_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" value="{{ old('preferred_date', isset($appointment) ? $appointment->preferred_date->format('Y-m-d') : '') }}" required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Heure <span class="text-danger">*</span></label>
                            <input type="time" name="appointment_time" class="form-control @error('appointment_time') is-invalid @enderror" value="{{ old('preferred_time', $appointment->preferred_time ?? '') }}" required>
                            @error('appointment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Statut <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', $appointment->status ?? 'pending') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmed" {{ old('status', $appointment->status ?? '') == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                                <option value="completed" {{ old('status', $appointment->status ?? '') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                <option value="cancelled" {{ old('status', $appointment->status ?? '') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Message du client</label>
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="3">{{ old('message', $appointment->message ?? '') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Notes administratives</label>
                            <textarea name="admin_notes" class="form-control @error('admin_notes') is-invalid @enderror" rows="3" placeholder="Notes internes...">{{ old('admin_notes', $appointment->admin_notes ?? '') }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ isset($appointment) ? 'Mettre à jour' : 'Créer' }}
                        </button>
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
