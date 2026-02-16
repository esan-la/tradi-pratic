@extends('layouts.admin')

@section('title', 'Publicités de Services')
@section('page-title', 'Gestion des Publicités de Services')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Publicités de Services</li>
@endsection

@section('page-actions')
@if(Auth::user()->hasPermission('pub-services.create'))
<a href="{{ route('admin.pub-services.create') }}" class="btn btn-primary-custom">
    <i class="fas fa-plus me-2"></i>Nouvelle Publicité
</a>
@endif
@endsection

@section('content')
<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div class="stat-details">
                @php
                    $total = \App\Models\PubService::count();
                @endphp
                <h3>{{ $total }}</h3>
                <p>Total Publicités</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                @php
                    $published = \App\Models\PubService::where('is_published', true)->count();
                @endphp
                <h3>{{ $published }}</h3>
                <p>Publiées</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                @php
                    $pending = \App\Models\PubService::where('is_published', false)->count();
                @endphp
                <h3>{{ $pending }}</h3>
                <p>En Attente</p>
            </div>
        </div>
    </div>
</div>

<div class="custom-card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Publicités</h5>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.pub-services.index') }}" method="GET" class="row g-2">
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Tous statuts</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publiées</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="user" class="form-select form-select-sm">
                            <option value="">Tous les users</option>
                            @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
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
                        <th>Image</th>
                        <th>Service</th>
                        <th>Prestataire</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td>
                            @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}"
                                     alt="{{ $service->title }}"
                                     class="rounded"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-bullhorn text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ Str::limit($service->title, 30) }}</strong><br>
                            <small class="text-muted">{{ $service->slug }}</small>
                        </td>
                        <td>
                            <strong>{{ $service->user->name }}</strong><br>
                            <small class="text-muted">{{ $service->user->email }}</small>
                        </td>
                        <td>
                            @if($service->price)
                                <strong class="text-success">{{ number_format($service->price, 0, ',', ' ') }} FCFA</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($service->is_published)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Publié
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>En attente
                                </span>
                            @endif
                        </td>
                        <td>{{ $service->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.pub-services.show', $service) }}"
                                   class="btn btn-sm btn-action btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(Auth::user()->hasPermission('pub-services.publish'))
                                <form action="{{ route('admin.pub-services.toggle-publish', $service) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm btn-action btn-outline-{{ $service->is_published ? 'warning' : 'success' }}"
                                            title="{{ $service->is_published ? 'Dépublier' : 'Publier' }}">
                                        <i class="fas fa-{{ $service->is_published ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                                @endif

                                @if(Auth::user()->hasPermission('pub-services.approve') && !$service->is_published)
                                <form action="{{ route('admin.pub-services.approve', $service) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-action btn-outline-success" title="Approuver">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif

                                @if(Auth::user()->hasPermission('pub-services.approve') && $service->is_published)
                                <form action="{{ route('admin.pub-services.reject', $service) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-action btn-outline-danger" title="Rejeter">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @endif

                                @if(Auth::user()->hasPermission('pub-services.edit'))
                                <a href="{{ route('admin.pub-services.edit', $service) }}"
                                   class="btn btn-sm btn-action btn-outline-info" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                @if(Auth::user()->hasPermission('pub-services.delete'))
                                <form action="{{ route('admin.pub-services.destroy', $service) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette publicité ?');">
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
                            <i class="fas fa-bullhorn fa-3x mb-3 d-block"></i>
                            Aucune publicité trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($services->hasPages())
    <div class="card-footer">
        {{ $services->links() }}
    </div>
    @endif
</div>
@endsection
