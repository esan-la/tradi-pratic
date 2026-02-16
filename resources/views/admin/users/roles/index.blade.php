@extends('layouts.admin')

@section('title', 'Rôles & Permissions')
@section('page-title', 'Gestion des Rôles')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Rôles & Permissions</li>
@endsection

@section('page-actions')
@if(Auth::user()->hasPermission('roles.create'))
<a href="{{ route('admin.roles.create') }}" class="btn btn-primary-custom">
    <i class="fas fa-plus me-2"></i>Nouveau Rôle
</a>
@endif
@endsection

@section('content')
<div class="row">
    @foreach($roles as $role)
    <div class="col-lg-6 mb-4">
        <div class="custom-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i>{{ ucfirst($role->name) }}
                </h5>
                <div>
                    @if(Auth::user()->hasPermission('roles.view'))
                    <a href="{{ route('admin.roles.show', $role) }}"
                       class="btn btn-sm btn-outline-primary" title="Voir détails">
                        <i class="fas fa-eye"></i>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('roles.edit'))
                    <a href="{{ route('admin.roles.edit', $role) }}"
                       class="btn btn-sm btn-outline-success" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('roles.delete') && !in_array($role->name, ['super_admin', 'admin']))
                    <form action="{{ route('admin.roles.destroy', $role) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Supprimer ce rôle ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if($role->description)
                <p class="text-muted mb-3">{{ $role->description }}</p>
                @endif

                <div class="row mb-3">
                    <div class="col-6">
                        <div class="stat-card-mini bg-primary bg-gradient">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0 text-white">{{ $role->users_count }}</h4>
                                    <small class="text-white">Utilisateurs</small>
                                </div>
                                <i class="fas fa-users fa-2x text-white opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card-mini bg-success bg-gradient">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0 text-white">{{ $role->permissions_count }}</h4>
                                    <small class="text-white">Permissions</small>
                                </div>
                                <i class="fas fa-key fa-2x text-white opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->hasPermission('roles.edit'))
                <div class="d-grid">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-primary">
                        <i class="fas fa-sliders-h me-2"></i>Gérer les permissions
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Info Box -->
<div class="custom-card mt-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations sur les Rôles</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="mb-3">Rôles système (protégés)</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-shield-alt text-danger me-2"></i>
                        <strong>Super Admin</strong> - Accès complet au système
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-shield-alt text-warning me-2"></i>
                        <strong>Admin</strong> - Gestion complète sauf suppressions critiques
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3">Rôles personnalisables</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-briefcase text-success me-2"></i>
                        <strong>Manager</strong> - Gestion du contenu
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-hotel text-info me-2"></i>
                        <strong>Receptionist</strong> - Gestion hôtelière
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-headset text-primary me-2"></i>
                        <strong>Customer Service</strong> - Support client
                    </li>
                </ul>
            </div>
        </div>

        <div class="alert alert-warning mt-3 mb-0">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Important :</strong> Les rôles Super Admin et Admin ne peuvent pas être supprimés.
            Assurez-vous qu'au moins un utilisateur possède le rôle Super Admin.
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-card-mini {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 0;
}
</style>
@endpush
