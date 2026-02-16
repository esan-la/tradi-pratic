@extends('layouts.admin')

@section('title', 'Modifier Utilisateur')
@section('page-title', 'Modifier l\'Utilisateur')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
<li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Informations de l'utilisateur</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom complet *</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="tel"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3"><i class="fas fa-key me-2"></i>Changer le mot de passe (optionnel)</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Laisser vide pour ne pas changer</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmer nouveau mot de passe</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3"><i class="fas fa-shield-alt me-2"></i>Rôles *</h6>

                    <div class="mb-3">
                        @foreach($roles as $role)
                        <div class="form-check mb-2">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="roles[]"
                                   value="{{ $role->id }}"
                                   id="role{{ $role->id }}"
                                   {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role{{ $role->id }}">
                                <strong>{{ ucfirst($role->name) }}</strong>
                                @if($role->description)
                                    <br><small class="text-muted">{{ $role->description }}</small>
                                @endif
                            </label>
                        </div>
                        @endforeach
                        @error('roles')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Info utilisateur -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Avatar</label>
                    <div class="text-center">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                 class="rounded-circle mb-2"
                                 width="100" height="100"
                                 alt="{{ $user->name }}">
                        @else
                            <div class="bg-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                                 style="width: 100px; height: 100px;">
                                <span class="text-white fw-bold fs-1">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="text-muted small">Inscription</label>
                    <p class="mb-0"><strong>{{ $user->created_at->format('d/m/Y H:i') }}</strong></p>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Dernière modification</label>
                    <p class="mb-0"><strong>{{ $user->updated_at->format('d/m/Y H:i') }}</strong></p>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Statut actuel</label>
                    <p class="mb-0">
                        @if($user->is_active ?? true)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-danger">Inactif</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Rôles actuels -->
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Rôles actuels</h5>
            </div>
            <div class="card-body">
                @if($user->roles->count() > 0)
                    @foreach($user->roles as $role)
                        <div class="mb-2">
                            <span class="badge bg-info me-2">{{ ucfirst($role->name) }}</span>
                            <small class="text-muted">{{ $role->permissions->count() }} permissions</small>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">Aucun rôle assigné</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
