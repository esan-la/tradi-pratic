@extends('layouts.admin')

@section('title', 'Créer un Utilisateur')
@section('page-title', 'Nouvel Utilisateur')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
<li class="breadcrumb-item active">Créer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations de l'utilisateur</h5>
            </div>
            <div class="card-body">
                {{-- ✅ Ajout de enctype pour l'upload d'avatar --}}
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- ✅ Photo de profil (avatar) --}}
                    <div class="mb-4">
                        <label class="form-label">Photo de profil</label>
                        <div class="d-flex align-items-start gap-3">
                            <div>
                                <img id="avatarPreview"
                                     src="{{ asset('images/default-avatar.png') }}"
                                     alt="Avatar"
                                     class="rounded-circle"
                                     style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #dee2e6;">
                            </div>
                            <div class="flex-grow-1">
                                <input type="file"
                                       name="avatar"
                                       id="avatarInput"
                                       class="form-control @error('avatar') is-invalid @enderror"
                                       accept="image/*"
                                       onchange="previewAvatar(this)">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle"></i> Formats acceptés: JPG, PNG, GIF. Taille max: 2MB
                                </small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- ✅ Nom et Prénom séparés --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom *</label>
                            <input type="text"
                                   name="nom"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}"
                                   placeholder="Dupont"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom *</label>
                            <input type="text"
                                   name="prenom"
                                   class="form-control @error('prenom') is-invalid @enderror"
                                   value="{{ old('prenom') }}"
                                   placeholder="Jean"
                                   required>
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email et Téléphone --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="jean.dupont@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="tel"
                                   name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}"
                                   placeholder="+226 XX XX XX XX">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Mot de passe --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mot de passe *</label>
                            <div class="input-group">
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required>
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 caractères</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmer mot de passe *</label>
                            <div class="input-group">
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control"
                                       required>
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmationIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Rôles --}}
                    <div class="mb-3">
                        <label class="form-label">Rôles * <small class="text-muted">(au moins un)</small></label>
                        <div class="border rounded p-3 bg-light">
                            @foreach($roles as $role)
                            <div class="form-check mb-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="roles[]"
                                       value="{{ $role->id }}"
                                       id="role{{ $role->id }}"
                                       {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role{{ $role->id }}">
                                    <strong class="text-primary">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</strong>
                                    @if($role->description)
                                        <br><small class="text-muted">{{ $role->description }}</small>
                                    @endif
                                    <span class="badge bg-secondary ms-2">{{ $role->permissions->count() }} permissions</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Boutons --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Créer l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        {{-- Informations sur les rôles --}}
        <div class="custom-card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Rôles disponibles</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($roles as $role)
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</h6>
                                <small class="text-muted">{{ $role->description }}</small>
                            </div>
                            <span class="badge bg-primary">{{ $role->permissions->count() }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Avertissement --}}
        <div class="custom-card">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention :</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        <li>L'utilisateur pourra se connecter immédiatement</li>
                        <li>Un email de bienvenue sera envoyé (si configuré)</li>
                        <li>Assurez-vous d'attribuer le bon rôle</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Preview avatar avant upload
function previewAvatar(input) {
    const preview = document.getElementById('avatarPreview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + 'Icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
