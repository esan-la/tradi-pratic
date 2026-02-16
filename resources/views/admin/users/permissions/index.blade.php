@extends('layouts.admin')

@section('title', 'Permissions du Système')
@section('page-title', 'Gestion des Permissions')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Rôles</a></li>
<li class="breadcrumb-item active">Permissions</li>
@endsection

@section('content')
<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-key"></i>
            </div>
            <div class="stat-details">
                @php
                    $totalPermissions = \App\Models\Permission::count();
                @endphp
                <h3>{{ $totalPermissions }}</h3>
                <p>Total Permissions</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-folder-open"></i>
            </div>
            <div class="stat-details">
                @php
                    $permissionGroups = \App\Models\Permission::all()->groupBy(function($p) {
                        return explode('.', $p->name)[0];
                    });
                @endphp
                <h3>{{ $permissionGroups->count() }}</h3>
                <p>Groupes/Modules</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-details">
                <h3>{{ \App\Models\Role::count() }}</h3>
                <p>Rôles Actifs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3>{{ \App\Models\User::count() }}</h3>
                <p>Utilisateurs</p>
            </div>
        </div>
    </div>
</div>

<!-- Info Box -->
<div class="custom-card mb-4">
    <div class="card-body">
        <div class="alert alert-info mb-0">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-2">
                        <i class="fas fa-info-circle me-2"></i>À propos des Permissions
                    </h5>
                    <p class="mb-0">
                        Les permissions sont automatiquement créées et gérées par le système.
                        Elles sont organisées par modules (hotels, products, orders, etc.) et par actions (view, create, edit, delete).
                        Utilisez les rôles pour assigner des groupes de permissions aux utilisateurs.
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">
                        <i class="fas fa-shield-alt me-2"></i>Gérer les Rôles
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Permissions par Module -->
@foreach($permissionGroups as $group => $permissions)
<div class="custom-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-folder-open me-2 text-primary"></i>
            {{ ucfirst($group) }}
        </h5>
        <span class="badge bg-primary">{{ $permissions->count() }} permissions</span>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($permissions as $permission)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="permission-item p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <i class="fas fa-key text-success me-2"></i>
                                <strong>{{ $permission->name }}</strong>
                            </h6>
                            @if($permission->description)
                            <p class="text-muted small mb-2">{{ $permission->description }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Rôles ayant cette permission -->
                    @php
                        $rolesWithPermission = \App\Models\Role::whereHas('permissions', function($q) use ($permission) {
                            $q->where('permissions.id', $permission->id);
                        })->get();
                    @endphp

                    @if($rolesWithPermission->count() > 0)
                    <div class="mt-2 pt-2 border-top">
                        <small class="text-muted d-block mb-1">Rôles ayant cette permission :</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($rolesWithPermission as $role)
                            <a href="{{ route('admin.roles.show', $role) }}" class="badge bg-info text-decoration-none">
                                {{ ucfirst($role->name) }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="mt-2 pt-2 border-top">
                        <small class="text-muted">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Non assignée
                        </small>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach

<!-- Matrice Rôles x Permissions -->
<div class="custom-card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-table me-2"></i>Matrice Rôles × Permissions
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="min-width: 150px;">Permission</th>
                        @foreach(\App\Models\Role::all() as $role)
                        <th class="text-center" style="min-width: 100px;">
                            {{ ucfirst($role->name) }}
                            <br>
                            <small class="text-muted">({{ $role->permissions->count() }})</small>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissionGroups as $group => $permissions)
                    <tr class="table-secondary">
                        <td colspan="{{ \App\Models\Role::count() + 1 }}">
                            <strong><i class="fas fa-folder me-2"></i>{{ ucfirst($group) }}</strong>
                        </td>
                    </tr>
                    @foreach($permissions as $permission)
                    <tr>
                        <td>
                            <small><strong>{{ explode('.', $permission->name)[1] }}</strong></small>
                        </td>
                        @foreach(\App\Models\Role::all() as $role)
                        <td class="text-center">
                            @if($role->permissions->contains($permission->id))
                                <i class="fas fa-check-circle text-success"></i>
                            @else
                                <i class="fas fa-times-circle text-muted opacity-25"></i>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.permission-item {
    background: #f8f9fa;
    transition: all 0.3s;
    height: 100%;
}

.permission-item:hover {
    background: #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table-responsive {
    max-height: 600px;
    overflow-y: auto;
}

.table thead {
    position: sticky;
    top: 0;
    z-index: 10;
    background: white;
}
</style>
@endpush
