@extends('layouts.admin')

@section('title', 'Créer un Rôle')
@section('page-title', 'Nouveau Rôle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Rôles</a></li>
<li class="breadcrumb-item active">Créer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Informations de Base -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Informations du Rôle</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du rôle <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               placeholder="ex: manager, receptionist">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Utilisez un nom en minuscules sans espaces (ex: customer_service)
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="3"
                                  placeholder="Décrivez les responsabilités de ce rôle...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">
                        <i class="fas fa-key me-2"></i>Permissions du Rôle
                        <span class="badge bg-secondary ms-2" id="selectedCount">0 sélectionnée(s)</span>
                    </h6>

                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="selectAll()">
                            <i class="fas fa-check-square me-1"></i>Tout Sélectionner
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">
                            <i class="fas fa-square me-1"></i>Tout Désélectionner
                        </button>
                    </div>

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
                                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permission{{ $permission->id }}">
                                            <strong>{{ explode('.', $permission->name)[1] }}</strong>
                                            @if($permission->description)
                                                <br><small class="text-muted">{{ $permission->description }}</small>
                                            @endif
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

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Créer le Rôle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Guide -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Guide de Création</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Bonnes pratiques :</strong>
                    <ul class="mb-0 mt-2 small">
                        <li>Choisissez un nom descriptif et unique</li>
                        <li>Utilisez le format snake_case (ex: customer_service)</li>
                        <li>Ajoutez une description claire</li>
                        <li>Sélectionnez uniquement les permissions nécessaires</li>
                        <li>Testez le rôle avec un utilisateur test</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Exemples -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Exemples de Rôles</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong class="text-primary">Manager</strong>
                    <p class="small text-muted mb-2">Gestion complète du contenu de la plateforme</p>
                    <div class="small">
                        <span class="badge bg-light text-dark border me-1">Réalisations</span>
                        <span class="badge bg-light text-dark border me-1">Recettes</span>
                        <span class="badge bg-light text-dark border me-1">Témoignages</span>
                        <span class="badge bg-light text-dark border">+10</span>
                    </div>
                </div>

                <div class="mb-3">
                    <strong class="text-success">Receptionist</strong>
                    <p class="small text-muted mb-2">Gestion des hôtels et réservations</p>
                    <div class="small">
                        <span class="badge bg-light text-dark border me-1">Hotels</span>
                        <span class="badge bg-light text-dark border me-1">Rooms</span>
                        <span class="badge bg-light text-dark border">Reservations</span>
                    </div>
                </div>

                <div class="mb-0">
                    <strong class="text-info">Customer Service</strong>
                    <p class="small text-muted mb-2">Support client et messages</p>
                    <div class="small">
                        <span class="badge bg-light text-dark border">Contacts</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques Permissions -->
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Permissions Disponibles</h5>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-key fa-3x text-primary mb-3"></i>
                <h3 class="mb-0">{{ \App\Models\Permission::count() }}</h3>
                <small class="text-muted">Permissions totales dans le système</small>

                <hr class="my-3">

                <div class="row text-center small">
                    @php
                        $permissionGroups = \App\Models\Permission::all()->groupBy(function($p) {
                            return explode('.', $p->name)[0];
                        });
                    @endphp
                    @foreach($permissionGroups->take(3) as $group => $perms)
                    <div class="col-12 mb-2">
                        <strong>{{ ucfirst($group) }}</strong>: {{ $perms->count() }}
                    </div>
                    @endforeach
                    @if($permissionGroups->count() > 3)
                    <div class="col-12">
                        <small class="text-muted">+ {{ $permissionGroups->count() - 3 }} autres groupes</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
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

    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
});

function selectAll() {
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelectedCount();
}

function deselectAll() {
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.permission-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count + ' sélectionnée(s)';
}
</script>
@endpush
