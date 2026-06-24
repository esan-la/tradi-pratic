@extends('layouts.admin')

@section('title', 'Journal des activites')
@section('page-title', 'Journal des activites')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Activites</li>
@endsection

@section('content')
<div class="custom-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-3 align-items-end">
            <div class="col-lg-3 col-md-6">
                <label for="search" class="form-label">Recherche</label>
                <input id="search" name="search" type="text" class="form-control" value="{{ request('search') }}" placeholder="Description, utilisateur...">
            </div>
            <div class="col-lg-2 col-md-6">
                <label for="causer_id" class="form-label">Utilisateur</label>
                <select id="causer_id" name="causer_id" class="form-select">
                    <option value="">Tous</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('causer_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label for="action" class="form-label">Log / Action</label>
                <input id="action" name="action" type="text" class="form-control" value="{{ request('action') }}" placeholder="default, system...">
            </div>
            <div class="col-lg-2 col-md-6">
                <label for="subject_type" class="form-label">Sujet</label>
                <select id="subject_type" name="subject_type" class="form-select">
                    <option value="">Tous</option>
                    @foreach($subjectTypes as $type)
                        <option value="{{ $type }}" {{ request('subject_type') === $type ? 'selected' : '' }}>
                            {{ class_basename($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-1 col-md-6">
                <label for="date_from" class="form-label">Du</label>
                <input id="date_from" name="date_from" type="date" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-lg-1 col-md-6">
                <label for="date_to" class="form-label">Au</label>
                <input id="date_to" name="date_to" type="date" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-lg-1 col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-success" title="Filtrer">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary" title="Reinitialiser">
                    <i class="fas fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="custom-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Log</th>
                        <th>Description</th>
                        <th>Sujet</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $log->created_at?->format('d/m/Y') }}</span><br>
                                <small class="text-muted">{{ $log->created_at?->format('H:i:s') }}</small>
                            </td>
                            <td>{{ $log->user_name }}</td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $log->log_name ?? 'default' }}</span>
                                @if($log->event)
                                    <span class="badge bg-info text-dark">{{ $log->event }}</span>
                                @endif
                            </td>
                            <td style="max-width: 440px;">{{ Str::limit($log->description, 120) }}</td>
                            <td>{{ $log->subject_type ? class_basename($log->subject_type) : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.activity-logs.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-clock-rotate-left fa-2x d-block mb-3"></i>
                                Aucune activite trouvee.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
        <div class="card-footer bg-white">
            {{ $logs->links() }}
        </div>
    @endif
</div>

<div class="custom-card mt-4">
    <div class="card-body">
        <form action="{{ route('admin.activity-logs.clear') }}" method="POST" class="row g-3 align-items-end" onsubmit="return confirm('Supprimer les anciens logs ?')">
            @csrf
            @method('DELETE')
            <div class="col-md-4">
                <label for="days" class="form-label">Nettoyer les logs plus anciens que</label>
                <div class="input-group">
                    <input id="days" name="days" type="number" min="1" max="365" class="form-control" value="90" required>
                    <span class="input-group-text">jours</span>
                </div>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-trash me-2"></i>Nettoyer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
