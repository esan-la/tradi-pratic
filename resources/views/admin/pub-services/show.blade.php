@extends('layouts.admin')

@section('title', 'Détails du Service')
@section('page-title', 'Détails de la Publicité')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.pub-services.index') }}">Publicités</a></li>
<li class="breadcrumb-item active">{{ Str::limit($service->title, 30) }}</li>
@endsection

@section('page-actions')
@if(Auth::user()->hasPermission('pub-services.edit'))
<a href="{{ route('admin.pub-services.edit', $service) }}" class="btn btn-primary-custom">
    <i class="fas fa-edit me-2"></i>Modifier
</a>
@endif
@endsection

@section('content')
<div class="row">
    <!-- Contenu Principal -->
    <div class="col-lg-8">
        <!-- Informations du Service -->
        <div class="custom-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Informations du Service</h5>
                <div>
                    @if($service->is_published)
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Publié
                        </span>
                    @else
                        <span class="badge bg-warning">
                            <i class="fas fa-clock me-1"></i>En attente
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($service->image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $service->image) }}"
                         alt="{{ $service->title }}"
                         class="img-fluid rounded"
                         style="max-height: 400px; width: 100%; object-fit: cover;">
                </div>
                @endif

                <h3 class="mb-3">{{ $service->title }}</h3>

                @if($service->price)
                <div class="mb-3">
                    <span class="badge bg-success bg-gradient p-3 fs-5">
                        <i class="fas fa-tag me-2"></i>{{ number_format($service->price, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                @endif

                <div class="mb-4">
                    <label class="text-muted small">Slug (URL)</label>
                    <p class="mb-0"><code>{{ $service->slug }}</code></p>
                </div>

                <div class="mb-4">
                    <label class="text-muted small mb-2">Description</label>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($service->description)) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Date de création</label>
                        <p class="mb-0"><strong>{{ $service->created_at->format('d/m/Y H:i') }}</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Dernière modification</label>
                        <p class="mb-0"><strong>{{ $service->updated_at->format('d/m/Y H:i') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prestataire -->
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations Prestataire</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if($service->user->avatar)
                        <img src="{{ asset('storage/' . $service->user->avatar) }}"
                             class="rounded-circle me-3"
                             width="60" height="60">
                    @else
                        <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 60px; height: 60px;">
                            <span class="text-white fw-bold fs-3">{{ substr($service->user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $service->user->name }}</h6>
                        <p class="text-muted mb-1">
                            <i class="fas fa-envelope me-1"></i>{{ $service->user->email }}
                        </p>
                        @if($service->user->phone)
                        <p class="text-muted mb-0">
                            <i class="fas fa-phone me-1"></i>{{ $service->user->phone }}
                        </p>
                        @endif
                    </div>
                    @if(Auth::user()->hasPermission('users.view'))
                    <div>
                        <a href="{{ route('admin.users.show', $service->user) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>Voir le Profil
                        </a>
                    </div>
                    @endif
                </div>

                <div class="row mt-3 pt-3 border-top">
                    <div class="col-md-6">
                        <div class="stat-mini p-3 border rounded text-center">
                            <i class="fas fa-bullhorn fa-2x text-primary mb-2"></i>
                            <h5 class="mb-0">{{ $service->user->pubServices->count() }}</h5>
                            <small class="text-muted">Publicités publiées</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-mini p-3 border rounded text-center">
                            <i class="fas fa-calendar fa-2x text-success mb-2"></i>
                            <h5 class="mb-0">{{ $service->user->created_at->format('d/m/Y') }}</h5>
                            <small class="text-muted">Membre depuis</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="col-lg-4">
        <!-- Actions -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(Auth::user()->hasPermission('pub-services.edit'))
                    <a href="{{ route('admin.pub-services.edit', $service) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('pub-services.publish'))
                    <form action="{{ route('admin.pub-services.toggle-publish', $service) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-{{ $service->is_published ? 'warning' : 'success' }} w-100">
                            <i class="fas fa-{{ $service->is_published ? 'eye-slash' : 'eye' }} me-2"></i>
                            {{ $service->is_published ? 'Dépublier' : 'Publier' }}
                        </button>
                    </form>
                    @endif

                    @if(Auth::user()->hasPermission('pub-services.approve'))
                        @if(!$service->is_published)
                        <form action="{{ route('admin.pub-services.approve', $service) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fas fa-check me-2"></i>Approuver
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.pub-services.reject', $service) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-ban me-2"></i>Rejeter
                            </button>
                        </form>
                        @endif
                    @endif

                    @if(Auth::user()->hasPermission('pub-services.view'))
                    <a href="{{ route('pub-services.show', $service->slug) }}"
                       class="btn btn-outline-secondary"
                       target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>Voir sur le Site
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('pub-services.delete'))
                    <form action="{{ route('admin.pub-services.destroy', $service) }}"
                          method="POST"
                          onsubmit="return confirm('Supprimer définitivement cette publicité ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="stat-item mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-eye text-primary me-2"></i>
                            <span class="text-muted">Vues</span>
                        </div>
                        <strong>{{ $service->views ?? 0 }}</strong>
                    </div>
                </div>

                <div class="stat-item mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-clock text-success me-2"></i>
                            <span class="text-muted">Publié depuis</span>
                        </div>
                        <strong>{{ $service->created_at->diffForHumans() }}</strong>
                    </div>
                </div>

                <div class="stat-item mb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-calendar text-info me-2"></i>
                            <span class="text-muted">Date de création</span>
                        </div>
                        <strong>{{ $service->created_at->format('d/m/Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statut -->
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Statut</h5>
            </div>
            <div class="card-body">
                <div class="mb-0">
                    <label class="text-muted small">Publication</label>
                    <p class="mb-0">
                        @if($service->is_published)
                            <span class="badge bg-success p-2">
                                <i class="fas fa-check-circle me-1"></i>Publié
                            </span>
                            <br><small class="text-muted mt-1 d-block">Visible sur le site public</small>
                        @else
                            <span class="badge bg-warning p-2">
                                <i class="fas fa-clock me-1"></i>En attente de modération
                            </span>
                            <br><small class="text-muted mt-1 d-block">Non visible sur le site</small>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-mini:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s;
}

.stat-item:hover {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 8px;
    margin: -8px;
    transition: all 0.3s;
}
</style>
@endpush
