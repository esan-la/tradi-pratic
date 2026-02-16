@extends('layouts.admin')

@section('title', 'Rôle : ' . ucfirst($role->name))
@section('page-title', 'Détails du Rôle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Rôles</a></li>
<li class="breadcrumb-item active">{{ ucfirst($role->name) }}</li>
@endsection

@section('page-actions')
@if(Auth::user()->hasPermission('roles.edit'))
<a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary-custom">
    <i class="fas fa-edit me-2"></i>Modifier
</a>
@endif
@endsection

@section('content')
<div class="row">
    <!-- Informations Principales -->
    <div class="col-lg-4">
        <div class="custom-card mb-4">
            <div class="card-body text-center">
                <div class="role-icon-large mb-3">
                    <i class="fas fa-shield-alt fa-5x text-primary"></i>
                </div>

                <h3 class="mb-2">{{ ucfirst($role->name) }}</h3>

                @if($role->description)
                <p class="text-muted mb-4">{{ $role->description }}</p>
                @else
                <p class="text-muted mb-4">Aucune description</p>
                @endif

                @if(in_array($role->name, ['super_admin', 'admin']))
                <span class="badge bg-danger mb-3">
                    <i class="fas fa-lock me-1"></i>Rôle Système Protégé
                </span>
                @endif

                <hr>

                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stat-mini-box">
                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                            <h4 class="mb-0">{{ $role->users->count() }}</h4>
                            <small class="text-muted">Utilisateurs</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-mini-box">
                            <i class="fas fa-key fa-2x text-success mb-2"></i>
                            <h4 class="mb-0">{{ $role->permissions->count() }}</h4>
                            <small class="text-muted">Permissions</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-start">
                    <div class="mb-2">
                        <label class="text-muted small">Créé le</label>
                        <p class="mb-0"><strong>{{ $role->created_at->format('d/m/Y H:i') }}</strong></p>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small">Dernière modification</label>
                        <p class="mb-0"><strong>{{ $role->updated_at->format('d/m/Y H:i') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if(Auth::user()->hasPermission('roles.edit') || Auth::user()->hasPermission('roles.delete'))
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(Auth::user()->hasPermission('roles.edit'))
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Modifier le Rôle
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('roles.delete') && !in_array($role->name, ['super_admin', 'admin']))
                        @if($role->users->count() == 0)
                        <form action="{{ route('admin.roles.destroy', $role) }}"
                              method="POST"
                              onsubmit="return confirm('Supprimer définitivement ce rôle ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Supprimer le Rôle
                            </button>
                        </form>
                        @else
                        <button class="btn btn-outline-danger w-100" disabled title="Ce rôle est assigné à des utilisateurs">
                            <i class="fas fa-lock me-2"></i>Suppression Impossible
                        </button>
                        <small class="text-danger">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ $role->users->count() }} utilisateur(s) ont ce rôle
                        </small>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Détails et Permissions -->
    <div class="col-lg-8">
        <!-- Statistiques -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="stat-card-detail p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Couverture Permissions</h6>
                                    @php
                                        $totalPermissions = \App\Models\Permission::count();
                                        $rolePermissions = $role->permissions->count();
                                        $percentage = $totalPermissions > 0 ? round(($rolePermissions / $totalPermissions) * 100) : 0;
                                    @endphp
                                    <h3 class="mb-0">{{ $percentage }}%</h3>
                                </div>
                                <i class="fas fa-percentage fa-3x text-primary opacity-25"></i>
                            </div>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                            </div>
                            <small class="text-muted">{{ $rolePermissions }} / {{ $totalPermissions }} permissions</small>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="stat-card-detail p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Utilisateurs Actifs</h6>
                                    <h3 class="mb-0">{{ $role->users->count() }}</h3>
                                </div>
                                <i class="fas fa-users fa-3x text-success opacity-25"></i>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                Utilisateurs ayant ce rôle
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Groupées -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-key me-2"></i>Permissions du Rôle
                    <span class="badge bg-primary ms-2">{{ $role->permissions->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($permissionsByGroup->count() > 0)
                <div class="row">
                    @foreach($permissionsByGroup as $group => $permissions)
                    <div class="col-md-6 mb-4">
                        <div class="permission-group-card p-3 border rounded">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-folder-open me-2"></i>{{ ucfirst($group) }}
                                <span class="badge bg-light text-dark ms-2">{{ $permissions->count() }}</span>
                            </h6>
                            <div class="permission-badges">
                                @foreach($permissions as $permission)
                                <span class="badge bg-success bg-gradient me-1 mb-2">
                                    <i class="fas fa-check me-1"></i>{{ explode('.', $permission->name)[1] }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-key fa-3x mb-3"></i>
                    <p>Aucune permission assignée à ce rôle</p>
                    @if(Auth::user()->hasPermission('roles.edit'))
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Ajouter des Permissions
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Utilisateurs avec ce Rôle -->
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Utilisateurs avec ce Rôle
                    <span class="badge bg-secondary ms-2">{{ $role->users->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($role->users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Statut</th>
                                <th>Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($role->users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                                 class="rounded-circle me-2"
                                                 width="32" height="32">
                                        @else
                                            <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                 style="width: 32px; height: 32px;">
                                                <small class="text-white fw-bold">{{ substr($user->name, 0, 1) }}</small>
                                            </div>
                                        @endif
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->is_active ?? true)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if(Auth::user()->hasPermission('users.view'))
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-users-slash fa-3x mb-3"></i>
                    <p>Aucun utilisateur n'a ce rôle pour le moment</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-mini-box {
    padding: 1rem;
    border-radius: 8px;
    transition: all 0.3s;
}

.stat-mini-box:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.permission-group-card {
    background: #f8f9fa;
    transition: all 0.3s;
}

.permission-group-card:hover {
    background: #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-card-detail {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}
</style>
@endpush
