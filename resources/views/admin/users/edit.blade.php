{{-- resources/views/admin/users/edit.blade.php --}}
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
    {{-- ============================================ --}}
    {{-- FORMULAIRE PRINCIPAL --}}
    {{-- ============================================ --}}
    <div class="col-lg-8">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i>Modifier : {{ $user->prenom }} {{ $user->nom }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- ============================================ --}}
                    {{-- SECTION : Identité --}}
                    {{-- ============================================ --}}
                    <h6 class="mb-3 text-success">
                        <i class="fas fa-id-card me-2"></i>Identité
                    </h6>

                    <div class="row">
                        {{-- Prénom --}}
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">
                                Prénom <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text"
                                       id="prenom"
                                       name="prenom"
                                       class="form-control @error('prenom') is-invalid @enderror"
                                       value="{{ old('prenom', $user->prenom) }}"
                                       placeholder="Entrez le prénom"
                                       required>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Nom --}}
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">
                                Nom <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text"
                                       id="nom"
                                       name="nom"
                                       class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom', $user->nom) }}"
                                       placeholder="Entrez le nom"
                                       required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- SECTION : Contact --}}
                    {{-- ============================================ --}}
                    <h6 class="mb-3 mt-2 text-success">
                        <i class="fas fa-address-book me-2"></i>Contact
                    </h6>

                    <div class="row">
                        {{-- Email --}}
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}"
                                       placeholder="exemple@email.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Téléphone --}}
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel"
                                       id="phone"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="+225 XX XX XX XX XX">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- SECTION : Avatar --}}
                    {{-- ============================================ --}}
                    <h6 class="mb-3 mt-2 text-success">
                        <i class="fas fa-camera me-2"></i>Photo de profil
                    </h6>

                    <div class="mb-3">
                        <label for="avatar" class="form-label">Avatar</label>
                        <div class="d-flex align-items-center gap-3">
                            {{-- Aperçu avatar actuel --}}
                            <div id="avatar-preview-container">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                         class="rounded-circle border"
                                         width="80" height="80"
                                         style="object-fit: cover;"
                                         id="avatar-preview"
                                         alt="Avatar {{ $user->prenom }}">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center border"
                                         style="width: 80px; height: 80px; background-color: #2d5016;"
                                         id="avatar-placeholder">
                                        <span class="text-white fw-bold fs-3">
                                            {{ strtoupper(substr($user->prenom ?? 'U', 0, 1) . substr($user->nom ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Input fichier --}}
                            <div class="flex-grow-1">
                                <input type="file"
                                       id="avatar"
                                       name="avatar"
                                       class="form-control @error('avatar') is-invalid @enderror"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       onchange="previewAvatar(this)">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Formats : JPEG, PNG, JPG, GIF. Max : 2 Mo. Laisser vide pour conserver l'actuel.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- SECTION : Mot de passe --}}
                    {{-- ============================================ --}}
                    <hr class="my-4">

                    <h6 class="mb-3 text-success">
                        <i class="fas fa-key me-2"></i>Changer le mot de passe
                        <small class="text-muted fw-normal">(optionnel)</small>
                    </h6>

                    <div class="row">
                        {{-- Nouveau mot de passe --}}
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimum 8 caractères">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>Laisser vide pour ne pas changer
                            </small>

                            {{-- Indicateur de force --}}
                            <div class="mt-2">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" id="password-strength-bar"
                                         role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="password-strength-text" class="text-muted"></small>
                            </div>
                        </div>

                        {{-- Confirmation --}}
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="form-control"
                                       placeholder="Retapez le mot de passe">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password_confirmation', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- SECTION : Rôles --}}
                    {{-- ============================================ --}}
                    <hr class="my-4">

                    <h6 class="mb-3 text-success">
                        <i class="fas fa-shield-alt me-2"></i>Rôles <span class="text-danger">*</span>
                    </h6>

                    <div class="mb-3">
                        <div class="row">
                            @foreach($roles as $role)
                                @php
                                    $isChecked = in_array($role->id, old('roles', $user->roles->pluck('id')->toArray()));
                                @endphp
                                <div class="col-md-6 mb-2">
                                    <div class="form-check p-3 border rounded {{ $isChecked ? 'border-success bg-success bg-opacity-10' : '' }}"
                                         id="role-card-{{ $role->id }}">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="roles[]"
                                               value="{{ $role->id }}"
                                               id="role{{ $role->id }}"
                                               onchange="toggleRoleCard(this, {{ $role->id }})"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="role{{ $role->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ ucfirst($role->name) }}</strong>
                                                <span class="badge bg-secondary">
                                                    {{ $role->permissions->count() }} permissions
                                                </span>
                                            </div>
                                            @if($role->description ?? false)
                                                <small class="text-muted d-block mt-1">
                                                    {{ $role->description }}
                                                </small>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <div class="text-danger small mt-1">
                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- ============================================ --}}
                    {{-- BOUTONS D'ACTION --}}
                    {{-- ============================================ --}}
                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                        <div class="d-flex gap-2">
                            <button type="reset" class="btn btn-outline-warning">
                                <i class="fas fa-undo me-2"></i>Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SIDEBAR --}}
    {{-- ============================================ --}}
    <div class="col-lg-4">

        {{-- Carte profil --}}
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Profil</h5>
            </div>
            <div class="card-body text-center">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}"
                         class="rounded-circle border shadow-sm mb-3"
                         width="120" height="120"
                         style="object-fit: cover;"
                         alt="{{ $user->prenom }} {{ $user->nom }}">
                @else
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm"
                         style="width: 120px; height: 120px; background-color: #2d5016;">
                        <span class="text-white fw-bold" style="font-size: 2.5rem;">
                            {{ strtoupper(substr($user->prenom ?? 'U', 0, 1) . substr($user->nom ?? '', 0, 1)) }}
                        </span>
                    </div>
                @endif

                <h5 class="fw-bold mb-1">{{ $user->prenom }} {{ $user->nom }}</h5>
                <p class="text-muted mb-1">
                    <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                </p>
                @if($user->phone)
                    <p class="text-muted mb-2">
                        <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                    </p>
                @endif

                {{-- Rôles --}}
                <div class="mt-2">
                    @forelse($user->roles as $role)
                        @php
                            $roleColors = [
                                'admin' => 'danger',
                                'editeur' => 'primary',
                                'auteur' => 'info',
                                'prestataire' => 'warning',
                                'utilisateur' => 'secondary',
                            ];
                            $color = $roleColors[$role->name] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }} me-1">
                            <i class="fas fa-shield-alt me-1"></i>{{ ucfirst($role->name) }}
                        </span>
                    @empty
                        <span class="badge bg-secondary">Aucun rôle</span>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Informations système --}}
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted"><i class="fas fa-hashtag me-2"></i>ID</span>
                    <strong>#{{ $user->id }}</strong>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted"><i class="fas fa-calendar-plus me-2"></i>Inscription</span>
                    <strong>{{ $user->created_at->format('d/m/Y') }}</strong>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted"><i class="fas fa-clock me-2"></i>Modifié</span>
                    <strong>{{ $user->updated_at->diffForHumans() }}</strong>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted"><i class="fas fa-circle me-2"></i>Statut</span>
                    @if($user->statut === 'actif')
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Actif
                        </span>
                    @else
                        <span class="badge bg-danger">
                            <i class="fas fa-times-circle me-1"></i>Inactif
                        </span>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted"><i class="fas fa-envelope-open me-2"></i>Email vérifié</span>
                    @if($user->email_verified_at)
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>{{ $user->email_verified_at->format('d/m/Y') }}
                        </span>
                    @else
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-exclamation-triangle me-1"></i>Non vérifié
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Statistiques --}}
        {{-- <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-0 text-primary">
                                {{ method_exists($user, 'realisations') ? $user->realisations()->count() : 0 }}
                            </h4>
                            <small class="text-muted">Réalisations</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-0 text-warning">
                                {{ method_exists($user, 'recettes') ? $user->recettes()->count() : 0 }}
                            </h4>
                            <small class="text-muted">Recettes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- Actions rapides --}}
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions rapides</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info w-100 mb-2">
                    <i class="fas fa-eye me-2"></i>Voir le profil complet
                </a>

                <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-envelope me-2"></i>Envoyer un email
                </a>

                @if(auth()->id() !== $user->id)
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                          onsubmit="return confirm('⚠️ Supprimer {{ $user->prenom }} {{ $user->nom }} ?\n\nCette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer l'utilisateur
                        </button>
                    </form>
                @else
                    <button class="btn btn-outline-danger w-100" disabled title="Vous ne pouvez pas vous supprimer">
                        <i class="fas fa-ban me-2"></i>Impossible de se supprimer
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /**
     * Toggle password visibility
     */
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    /**
     * Password strength indicator
     */
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const bar = document.getElementById('password-strength-bar');
        const text = document.getElementById('password-strength-text');

        if (password.length === 0) {
            bar.style.width = '0%';
            text.textContent = '';
            return;
        }

        let strength = 0;

        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]/)) strength += 25;
        if (password.match(/[A-Z]/)) strength += 25;
        if (password.match(/[0-9]/) || password.match(/[^a-zA-Z0-9]/)) strength += 25;

        bar.style.width = strength + '%';

        if (strength <= 25) {
            bar.className = 'progress-bar bg-danger';
            text.textContent = '🔴 Faible';
        } else if (strength <= 50) {
            bar.className = 'progress-bar bg-warning';
            text.textContent = '🟡 Moyen';
        } else if (strength <= 75) {
            bar.className = 'progress-bar bg-info';
            text.textContent = '🔵 Bon';
        } else {
            bar.className = 'progress-bar bg-success';
            text.textContent = '🟢 Fort';
        }
    });

    /**
     * Preview avatar before upload
     */
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            if (file.size > 2 * 1024 * 1024) {
                alert('⚠️ Fichier trop volumineux. Maximum : 2 Mo.');
                input.value = '';
                return;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('⚠️ Format non supporté. Utilisez : JPEG, PNG, JPG ou GIF.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const container = document.getElementById('avatar-preview-container');
                container.innerHTML = `
                    <img src="${e.target.result}"
                         class="rounded-circle border border-success border-2"
                         width="80" height="80"
                         style="object-fit: cover;"
                         alt="Aperçu avatar">
                `;
            };
            reader.readAsDataURL(file);
        }
    }

    /**
     * Toggle role card highlight
     */
    function toggleRoleCard(checkbox, roleId) {
        const card = document.getElementById('role-card-' + roleId);
        if (checkbox.checked) {
            card.classList.add('border-success', 'bg-success', 'bg-opacity-10');
        } else {
            card.classList.remove('border-success', 'bg-success', 'bg-opacity-10');
        }
    }
</script>
@endpush
