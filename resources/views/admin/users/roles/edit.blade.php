@extends('layouts.admin')

@section('title', 'Modifier le Rôle : ' . $role->name)
@section('page-title', 'Modifier le Rôle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Rôles</a></li>
<li class="breadcrumb-item active">{{ ucfirst($role->name) }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Formulaire Principal -->
    <div class="col-lg-8">
        <!-- Informations de Base -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Informations du Rôle</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du rôle <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $role->name) }}"
                               required
                               {{ in_array($role->name, ['super_admin', 'admin']) ? 'readonly' : '' }}>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if(in_array($role->name, ['super_admin', 'admin']))
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>Rôle système protégé - Le nom ne peut pas être modifié
                            </small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="3">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Gestion des Permissions -->
        <div class="custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-key me-2"></i>Permissions du Rôle</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="selectAll()">
                        <i class="fas fa-check-square me-1"></i>Tout Sélectionner
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">
                        <i class="fas fa-square me-1"></i>Tout Désélectionner
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.permissions', $role) }}" method="POST" id="permissionsForm">
                    @csrf

                    @if(in_array($role->name, ['super_admin']))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Super Admin</strong> possède automatiquement toutes les permissions du système.
                        </div>
                    @endif

                    <div class="row">
                        @foreach($permissions as $group => $groupPermissions)
                        <div class="col-md-6 mb-4">
                            <div class="permission-card border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-folder-open me-2"></i>
                                        {{ ucfirst($group) }}
                                    </h6>
                                    <small class="text-muted">{{ $groupPermissions->count() }} permissions</small>
                                </div>

                                <div class="permission-list">
                                    @foreach($groupPermissions as $permission)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input permission-checkbox"
                                               type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->id }}"
                                               id="permission{{ $permission->id }}"
                                               {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}
                                               {{ in_array($role->name, ['super_admin']) ? 'checked disabled' : '' }}>
                                        <label class="form-check-label" for="permission{{ $permission->id }}">
                                            <strong>{{ explode('.', $permission->name)[1] }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $permission->description ?? $permission->name }}</small>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @error('permissions')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div>
                            <strong id="selectedCount">0</strong> permission(s) sélectionnée(s)
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Enregistrer les Permissions
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Informations -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Nom du rôle</label>
                    <p class="mb-0"><strong>{{ ucfirst($role->name) }}</strong></p>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Utilisateurs avec ce rôle</label>
                    <p class="mb-0">
                        <span class="badge bg-primary">{{ $role->users->count() }} utilisateurs</span>
                    </p>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Permissions actuelles</label>
                    <p class="mb-0">
                        <span class="badge bg-success">{{ $role->permissions->count() }} permissions</span>
                    </p>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Créé le</label>
                    <p class="mb-0">{{ $role->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="mb-0">
                    <label class="text-muted small">Dernière modification</label>
                    <p class="mb-0">{{ $role->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistiques</h5>
            </div>
            <div class="card-body">
                @php
                    $allPermissions = \App\Models\Permission::count();
                    $rolePermissions = $role->permissions->count();
                    $percentage = $allPermissions > 0 ? round(($rolePermissions / $allPermissions) * 100) : 0;
                @endphp

                <div class="mb-3">
                    <label class="text-muted small mb-2">Couverture des permissions</label>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: {{ $percentage }}%"
                             aria-valuenow="{{ $percentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            {{ $percentage }}%
                        </div>
                    </div>
                    <small class="text-muted">{{ $rolePermissions }} / {{ $allPermissions }} permissions</small>
                </div>

                <hr>

                <div class="text-center">
                    <i class="fas fa-users fa-3x text-primary mb-2"></i>
                    <h3 class="mb-0">{{ $role->users->count() }}</h3>
                    <small class="text-muted">Utilisateurs actifs</small>
                </div>
            </div>
        </div>

        <!-- Avertissement -->
        @if(in_array($role->name, ['super_admin', 'admin']))
        <div class="custom-card">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Rôle Système</strong>
                    <p class="mb-0 mt-2 small">
                        Ce rôle est protégé et ne peut pas être supprimé.
                        Modifiez les permissions avec précaution.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Utilisateurs avec ce rôle -->
        @if($role->users->count() > 0)
        <div class="custom-card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Utilisateurs</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($role->users->take(5) as $user)
                    <div class="list-group-item px-0">
                        <div class="d-flex align-items-center">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}"
                                     class="rounded-circle me-2"
                                     width="30" height="30"
                                     alt="{{ $user->name }}">
                            @else
                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                     style="width: 30px; height: 30px;">
                                    <small class="text-white fw-bold">{{ substr($user->name, 0, 1) }}</small>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <small><strong>{{ $user->name }}</strong></small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($role->users->count() > 5)
                    <div class="text-center mt-2">
                        <small class="text-muted">+ {{ $role->users->count() - 5 }} autres utilisateurs</small>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.permission-card {
    background: #f8f9fa;
    transition: all 0.3s;
}

.permission-card:hover {
    background: #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.permission-list {
    max-height: 300px;
    overflow-y: auto;
}

.permission-list::-webkit-scrollbar {
    width: 6px;
}

.permission-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.permission-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.permission-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedCount();

    // Update count when checkboxes change
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
});

function selectAll() {
    document.querySelectorAll('.permission-checkbox:not([disabled])').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelectedCount();
}

function deselectAll() {
    document.querySelectorAll('.permission-checkbox:not([disabled])').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.permission-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count;
}
</script>
@endpush
