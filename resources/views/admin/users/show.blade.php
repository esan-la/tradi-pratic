@extends('layouts.admin')

@section('title', 'Utilisateur : ' . $user->name)
@section('page-title', 'Profil Utilisateur')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
<li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('page-actions')
@if(Auth::user()->hasPermission('users.edit'))
<a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary-custom">
    <i class="fas fa-edit me-2"></i>Modifier
</a>
@endif
@endsection

@section('content')
<div class="row">
    <!-- Profil Principal -->
    <div class="col-lg-4">
        <div class="custom-card mb-4">
            <div class="card-body text-center">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}"
                         class="rounded-circle mb-3"
                         width="150" height="150"
                         alt="{{ $user->name }}">
                @else
                    <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                         style="width: 150px; height: 150px;">
                        <span class="text-white fw-bold" style="font-size: 4rem;">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    </div>
                @endif

                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>

                @if($user->is_active ?? true)
                    <span class="badge bg-success mb-3">
                        <i class="fas fa-check-circle me-1"></i>Compte Actif
                    </span>
                @else
                    <span class="badge bg-danger mb-3">
                        <i class="fas fa-times-circle me-1"></i>Compte Inactif
                    </span>
                @endif

                <hr>

                <div class="text-start">
                    @if($user->phone)
                    <p class="mb-2">
                        <i class="fas fa-phone text-primary me-2"></i>
                        <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                    </p>
                    @endif

                    <p class="mb-2">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    </p>

                    <p class="mb-2">
                        <i class="fas fa-calendar text-primary me-2"></i>
                        Inscrit le {{ $user->created_at->format('d/m/Y') }}
                    </p>

                    <p class="mb-0">
                        <i class="fas fa-clock text-primary me-2"></i>
                        Dernière activité : {{ $user->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        @if(Auth::user()->hasPermission('users.edit') || Auth::user()->hasPermission('users.delete'))
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(Auth::user()->hasPermission('users.edit'))
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Modifier le Profil
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('users.edit') && method_exists($user, 'toggleStatus'))
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-{{ ($user->is_active ?? true) ? 'warning' : 'success' }} w-100">
                            <i class="fas fa-{{ ($user->is_active ?? true) ? 'ban' : 'check' }} me-2"></i>
                            {{ ($user->is_active ?? true) ? 'Désactiver' : 'Activer' }} le Compte
                        </button>
                    </form>
                    @endif

                    @if(Auth::user()->hasPermission('users.delete') && $user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}"
                          method="POST"
                          onsubmit="return confirm('Supprimer définitivement cet utilisateur ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer l'Utilisateur
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Détails et Activités -->
    <div class="col-lg-8">
        <!-- Rôles et Permissions -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Rôles et Permissions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h6 class="text-muted mb-3">Rôles Assignés</h6>
                        @if($user->roles->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                    <div class="role-badge">
                                        <span class="badge bg-info bg-gradient p-3">
                                            <i class="fas fa-shield-alt me-2"></i>
                                            <strong class="fs-6">{{ ucfirst($role->name) }}</strong>
                                            <br>
                                            <small>{{ $role->permissions->count() }} permissions</small>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Aucun rôle assigné</p>
                        @endif
                    </div>

                    <div class="col-md-12">
                        <h6 class="text-muted mb-3">Permissions Totales</h6>
                        @php
                            $allPermissions = $user->roles->flatMap->permissions->unique('id');
                        @endphp

                        @if($allPermissions->count() > 0)
                            <div class="row">
                                @php
                                    $permissionsByGroup = $allPermissions->groupBy(function($permission) {
                                        return explode('.', $permission->name)[0];
                                    });
                                @endphp

                                @foreach($permissionsByGroup as $group => $permissions)
                                <div class="col-md-6 mb-3">
                                    <div class="permission-group p-3 border rounded">
                                        <h6 class="text-primary mb-2">
                                            <i class="fas fa-folder-open me-2"></i>{{ ucfirst($group) }}
                                        </h6>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($permissions as $permission)
                                                <span class="badge bg-light text-dark border">
                                                    {{ explode('.', $permission->name)[1] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Aucune permission</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="stat-mini p-3 border rounded">
                            <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                            <h4 class="mb-0">{{ $user->created_at->diffInDays(now()) }}</h4>
                            <small class="text-muted">Jours d'ancienneté</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-mini p-3 border rounded">
                            <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                            <h4 class="mb-0">{{ $user->roles->count() }}</h4>
                            <small class="text-muted">Rôles Assignés</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-mini p-3 border rounded">
                            <i class="fas fa-key fa-2x text-info mb-2"></i>
                            <h4 class="mb-0">{{ $allPermissions->count() }}</h4>
                            <small class="text-muted">Permissions Totales</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique d'Activités -->
        @if(isset($user->activityLogs) && $user->activityLogs->count() > 0)
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Dernières Activités</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->activityLogs->take(10) as $log)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ $log->action ?? 'Action' }}</span>
                                </td>
                                <td>{{ Str::limit($log->description, 60) }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.role-badge .badge {
    font-size: 0.9rem;
}

.permission-group {
    background: #f8f9fa;
}

.permission-group:hover {
    background: #e9ecef;
    transition: background 0.3s;
}

.stat-mini:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s;
}
</style>
@endpush
