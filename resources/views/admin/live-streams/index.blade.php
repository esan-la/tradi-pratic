{{-- resources/views/admin/live-streams/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Gestion des Lives')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-broadcast-tower text-danger me-2"></i>Gestion des Lives
            </h2>
            <p class="text-muted mb-0">Programmez et gérez vos diffusions en direct</p>
        </div>
        <a href="{{ route('admin.live-streams.create') }}" class="btn btn-success btn-lg">
            <i class="fas fa-plus me-2"></i>Programmer un Live
        </a>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-primary">{{ $stats['total'] }}</div>
                <small class="text-muted">Total</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 {{ $stats['live'] > 0 ? 'border-danger border-2' : '' }}">
                <div class="fs-2 fw-bold text-danger">{{ $stats['live'] }}</div>
                <small class="text-muted">En Direct</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-info">{{ $stats['scheduled'] }}</div>
                <small class="text-muted">Programmés</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-secondary">{{ $stats['ended'] }}</div>
                <small class="text-muted">Terminés</small>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Rechercher</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Titre du live..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        @foreach(\App\Models\LiveStream::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="fas fa-search me-1"></i>Filtrer
                    </button>
                    <a href="{{ route('admin.live-streams.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 80px;">Aperçu</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Date Prévue</th>
                        <th>YouTube ID</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($streams as $stream)
                        <tr class="{{ $stream->is_live ? 'table-danger' : '' }}">
                            <td>
                                <img src="{{ $stream->thumbnail_url }}"
                                     alt="{{ $stream->title }}"
                                     class="rounded"
                                     width="70"
                                     height="40"
                                     style="object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $stream->title }}</strong>
                                @if($stream->is_featured)
                                    <span class="badge bg-warning text-dark ms-1">⭐</span>
                                @endif
                                @if($stream->description)
                                    <br><small class="text-muted">{{ Str::limit($stream->description, 60) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($stream->category)
                                    <span class="badge bg-success-subtle text-success">{{ $stream->category_label }}</span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $stream->status_color }}">
                                    @if($stream->is_live)
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem; animation: liveBlink 1s infinite;"></i>
                                    @endif
                                    {{ $stream->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($stream->scheduled_at)
                                    <small>
                                        {{ $stream->scheduled_at->format('d/m/Y') }}<br>
                                        <span class="text-muted">{{ $stream->scheduled_at->format('H:i') }}</span>
                                    </small>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>
                                @if($stream->youtube_video_id)
                                    <code class="small">{{ $stream->youtube_video_id }}</code>
                                @else
                                    <span class="text-warning small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Non défini
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    {{-- Actions selon le statut --}}
                                    @if($stream->status === 'scheduled')
                                        <form action="{{ route('admin.live-streams.go-live', $stream) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Démarrer ce live maintenant ?')">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-danger" title="Démarrer le live">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($stream->status === 'live')
                                        <form action="{{ route('admin.live-streams.end', $stream) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Terminer ce live ?')">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-warning" title="Terminer">
                                                <i class="fas fa-stop"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.live-streams.edit', $stream) }}"
                                       class="btn btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($stream->status === 'scheduled')
                                        <form action="{{ route('admin.live-streams.cancel', $stream) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Annuler ce live ?')">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-outline-warning" title="Annuler">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.live-streams.destroy', $stream) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer définitivement ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-video-slash fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-3">Aucun live trouvé</p>
                                <a href="{{ route('admin.live-streams.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>Programmer un live
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($streams->hasPages())
            <div class="card-footer">
                {{ $streams->links() }}
            </div>
        @endif
    </div>
</div>

<style>
@keyframes liveBlink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
</style>
@endsection
