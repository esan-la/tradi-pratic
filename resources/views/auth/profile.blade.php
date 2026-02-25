{{-- resources/views/auth/profile.blade.php --}}

@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<!-- Header Profil -->
<section class="profile-header bg-success text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="position-relative d-inline-block">
                    <img src="{{ $user->avatar_url }}"
                         alt="{{ $user->full_name }}"
                         class="rounded-circle border border-4 border-white shadow"
                         width="100"
                         height="100"
                         style="object-fit: cover;"
                         id="headerAvatar">
                    <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm"
                          style="cursor: pointer;"
                          data-bs-toggle="modal"
                          data-bs-target="#avatarModal"
                          title="Changer la photo">
                        <i class="fas fa-camera text-success small"></i>
                    </span>
                </div>
            </div>
            <div class="col">
                <h2 class="fw-bold mb-1">{{ $user->full_name }}</h2>
                <p class="mb-1 opacity-75">
                    <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                </p>
                @if($user->phone)
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-phone me-1"></i>+226 {{ $user->phone }}
                    </p>
                @endif
            </div>
            <div class="col-auto d-none d-md-block">
                @if($user->roles->count() > 0)
                    @foreach($user->roles as $role)
                        <span class="badge bg-white text-success fs-6 px-3 py-2">
                            <i class="fas fa-user-tag me-1"></i>{{ $role->name }}
                        </span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Contenu Profil -->
<section class="py-5 bg-light">
    <div class="container">

        <!-- Messages Flash Globaux -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-0">
                        <div class="nav flex-column nav-pills" id="profileTabs" role="tablist">
                            <button class="nav-link active text-start px-4 py-3 rounded-0 rounded-top-4"
                                    id="info-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#info-pane"
                                    type="button">
                                <i class="fas fa-user me-2"></i>Informations
                            </button>
                            <button class="nav-link text-start px-4 py-3 rounded-0"
                                    id="avatar-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#avatar-pane"
                                    type="button">
                                <i class="fas fa-camera me-2"></i>Photo de profil
                            </button>
                            <button class="nav-link text-start px-4 py-3 rounded-0 rounded-bottom-4"
                                    id="password-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#password-pane"
                                    type="button">
                                <i class="fas fa-lock me-2"></i>Mot de passe
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Infos compte -->
                <div class="card shadow-sm border-0 rounded-4 mt-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-info-circle me-1"></i>Informations du compte
                        </h6>
                        <div class="small">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Membre depuis :</span>
                                <span class="fw-semibold">{{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Dernière MAJ :</span>
                                <span class="fw-semibold">{{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                            @if($user->email_verified_at)
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Email :</span>
                                    <span class="text-success fw-semibold">
                                        <i class="fas fa-check-circle me-1"></i>Vérifié
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu des onglets -->
            <div class="col-lg-9">
                <div class="tab-content" id="profileTabsContent">

                    <!-- ===== ONGLET INFORMATIONS ===== -->
                    <div class="tab-pane fade show active" id="info-pane" role="tabpanel">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-bottom rounded-top-4 px-4 py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-edit text-success me-2"></i>
                                    Informations personnelles
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('auth.profile.update') }}" method="POST" id="profileForm">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="prenom" class="form-label fw-semibold">
                                                Prénom <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-user text-muted"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('prenom') is-invalid @enderror"
                                                       id="prenom"
                                                       name="prenom"
                                                       value="{{ old('prenom', $user->prenom) }}"
                                                       required>
                                            </div>
                                            @error('prenom')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="nom" class="form-label fw-semibold">
                                                Nom <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-user text-muted"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('nom') is-invalid @enderror"
                                                       id="nom"
                                                       name="nom"
                                                       value="{{ old('nom', $user->nom) }}"
                                                       required>
                                            </div>
                                            @error('nom')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="email" class="form-label fw-semibold">
                                                Adresse Email <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-envelope text-muted"></i>
                                                </span>
                                                <input type="email"
                                                       class="form-control @error('email') is-invalid @enderror"
                                                       id="email"
                                                       name="email"
                                                       value="{{ old('email', $user->email) }}"
                                                       required>
                                            </div>
                                            @error('email')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="phone" class="form-label fw-semibold">Téléphone</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-phone text-muted"></i>
                                                </span>
                                                <span class="input-group-text bg-light">+226</span>
                                                <input type="tel"
                                                       class="form-control @error('phone') is-invalid @enderror"
                                                       id="phone"
                                                       name="phone"
                                                       value="{{ old('phone', $user->phone) }}"
                                                       placeholder="70 00 00 00">
                                            </div>
                                            @error('phone')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success px-4" id="saveInfoBtn">
                                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ===== ONGLET PHOTO DE PROFIL ===== -->
                    <div class="tab-pane fade" id="avatar-pane" role="tabpanel">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-bottom rounded-top-4 px-4 py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-camera text-success me-2"></i>
                                    Photo de profil
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <!-- Aperçu actuel -->
                                    <div class="col-md-4 text-center mb-4 mb-md-0">
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ $user->avatar_url }}"
                                                 alt="{{ $user->full_name }}"
                                                 class="rounded-circle shadow"
                                                 width="180"
                                                 height="180"
                                                 style="object-fit: cover;"
                                                 id="avatarPreview">
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">Photo actuelle</p>

                                        @if($user->avatar)
                                            <form action="{{ route('auth.profile.avatar.delete') }}"
                                                  method="POST"
                                                  class="mt-2"
                                                  onsubmit="return confirm('Supprimer votre photo de profil ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash-alt me-1"></i>Supprimer la photo
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- Upload -->
                                    <div class="col-md-8">
                                        <form action="{{ route('auth.profile.avatar') }}"
                                              method="POST"
                                              enctype="multipart/form-data"
                                              id="avatarForm">
                                            @csrf
                                            @method('PUT')

                                            <div class="upload-zone-profile" id="avatarUploadZone">
                                                <div class="text-center p-4" id="avatarDropArea">
                                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                    <h6>Glissez votre photo ici</h6>
                                                    <p class="text-muted small mb-3">ou cliquez pour parcourir</p>
                                                    <button type="button"
                                                            class="btn btn-outline-success"
                                                            onclick="document.getElementById('avatarInput').click()">
                                                        <i class="fas fa-folder-open me-1"></i>Choisir une photo
                                                    </button>
                                                </div>
                                            </div>

                                            <input type="file"
                                                   class="d-none"
                                                   id="avatarInput"
                                                   name="avatar"
                                                   accept="image/jpeg,image/png,image/webp"
                                                   onchange="previewAvatar(this)">

                                            @error('avatar')
                                                <div class="text-danger small mt-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror

                                            <div class="mt-3">
                                                <div class="d-flex align-items-center text-muted small mb-1">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Formats : JPG, PNG, WebP
                                                </div>
                                                <div class="d-flex align-items-center text-muted small mb-1">
                                                    <i class="fas fa-expand-arrows-alt me-2"></i>
                                                    Taille recommandée : 300 x 300 px
                                                </div>
                                                <div class="d-flex align-items-center text-muted small">
                                                    <i class="fas fa-weight-hanging me-2"></i>
                                                    Poids maximum : 2 Mo
                                                </div>
                                            </div>

                                            <div class="mt-4" id="avatarSubmitSection" style="display: none;">
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-success" id="saveAvatarBtn">
                                                        <i class="fas fa-save me-1"></i>Enregistrer la photo
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-outline-secondary"
                                                            onclick="cancelAvatarChange()">
                                                        Annuler
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== ONGLET MOT DE PASSE ===== -->
                    <div class="tab-pane fade" id="password-pane" role="tabpanel">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-bottom rounded-top-4 px-4 py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-lock text-success me-2"></i>
                                    Changer le mot de passe
                                </h5>
                            </div>
                            <div class="card-body p-4">

                                @if(session('password_success'))
                                    <div class="alert alert-success alert-dismissible fade show">
                                        <i class="fas fa-check-circle me-2"></i>{{ session('password_success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <strong>Sécurité :</strong> Choisissez un mot de passe fort avec au moins 8 caractères,
                                    incluant majuscules, chiffres et caractères spéciaux.
                                </div>

                                <form action="{{ route('auth.profile.password') }}" method="POST" id="passwordForm">
                                    @csrf
                                    @method('PUT')

                                    <!-- Mot de passe actuel -->
                                    <div class="mb-4">
                                        <label for="current_password" class="form-label fw-semibold">
                                            Mot de passe actuel <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-key text-muted"></i>
                                            </span>
                                            <input type="password"
                                                   class="form-control @error('current_password') is-invalid @enderror"
                                                   id="current_password"
                                                   name="current_password"
                                                   placeholder="Votre mot de passe actuel"
                                                   required>
                                            <button class="input-group-text bg-light" type="button"
                                                    onclick="togglePwd('current_password', 'cpIcon')">
                                                <i class="fas fa-eye text-muted" id="cpIcon"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="text-danger small mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <hr class="my-4">

                                    <!-- Nouveau mot de passe -->
                                    <div class="mb-4">
                                        <label for="new_password" class="form-label fw-semibold">
                                            Nouveau mot de passe <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   id="new_password"
                                                   name="password"
                                                   placeholder="Minimum 8 caractères"
                                                   required>
                                            <button class="input-group-text bg-light" type="button"
                                                    onclick="togglePwd('new_password', 'npIcon')">
                                                <i class="fas fa-eye text-muted" id="npIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="text-danger small mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror

                                        <!-- Indicateur de force -->
                                        <div class="mt-2">
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar" id="pwdStrengthBar" style="width: 0%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <small class="text-muted" id="pwdStrengthText">Force du mot de passe</small>
                                            </div>
                                        </div>

                                        <!-- Règles -->
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <small class="d-block pwd-rule" id="pwd_rule_length">
                                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                                    8 caractères min.
                                                </small>
                                                <small class="d-block pwd-rule" id="pwd_rule_upper">
                                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                                    Une majuscule
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="d-block pwd-rule" id="pwd_rule_number">
                                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                                    Un chiffre
                                                </small>
                                                <small class="d-block pwd-rule" id="pwd_rule_special">
                                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                                    Un caractère spécial
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Confirmation -->
                                    <div class="mb-4">
                                        <label for="password_confirmation_profile" class="form-label fw-semibold">
                                            Confirmer le nouveau mot de passe <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password"
                                                   class="form-control"
                                                   id="password_confirmation_profile"
                                                   name="password_confirmation"
                                                   placeholder="Retapez le nouveau mot de passe"
                                                   required>
                                            <button class="input-group-text bg-light" type="button"
                                                    onclick="togglePwd('password_confirmation_profile', 'cpfIcon')">
                                                <i class="fas fa-eye text-muted" id="cpfIcon"></i>
                                            </button>
                                        </div>
                                        <small id="pwdMatchMsg" class="mt-1"></small>
                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success px-4" id="savePwdBtn">
                                            <i class="fas fa-key me-2"></i>Changer le mot de passe
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Avatar (accès rapide depuis le header) -->
<div class="modal fade" id="avatarModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-success text-white rounded-top-4">
                <h5 class="modal-title">
                    <i class="fas fa-camera me-2"></i>Changer la photo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img src="{{ $user->avatar_url }}"
                     alt="{{ $user->full_name }}"
                     class="rounded-circle shadow mb-3"
                     width="150"
                     height="150"
                     style="object-fit: cover;"
                     id="modalAvatarPreview">

                <form action="{{ route('auth.profile.avatar') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="modalAvatarForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <input type="file"
                               class="form-control"
                               name="avatar"
                               accept="image/jpeg,image/png,image/webp"
                               required
                               onchange="previewModalAvatar(this)">
                    </div>
                    <small class="text-muted d-block mb-3">JPG, PNG ou WebP • Max 2 Mo</small>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save me-1"></i>Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.profile-header {
    background: linear-gradient(135deg, #198754 0%, #157347 50%, #0f5132 100%);
}

/* Onglets */
.nav-pills .nav-link {
    color: #495057;
    border-left: 3px solid transparent;
    transition: all 0.3s;
}

.nav-pills .nav-link:hover {
    background: #f0fdf4;
    color: #198754;
    border-left-color: #198754;
}

.nav-pills .nav-link.active {
    background: #f0fdf4;
    color: #198754;
    border-left-color: #198754;
    font-weight: 600;
}

/* Upload Zone */
.upload-zone-profile {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    transition: all 0.3s;
    cursor: pointer;
    background: #fafafa;
}

.upload-zone-profile:hover,
.upload-zone-profile.drag-over {
    border-color: #198754;
    background: #f0fdf4;
}

/* Password rules */
.pwd-rule {
    color: #6c757d;
    transition: color 0.3s;
}

.pwd-rule.valid {
    color: #198754 !important;
    font-weight: 500;
}

.pwd-rule.valid i {
    color: #198754 !important;
}

/* Form focus */
.form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
}

/* Responsive */
@media (max-width: 768px) {
    .profile-header {
        text-align: center;
    }

    .profile-header .row {
        flex-direction: column;
        align-items: center !important;
    }

    .profile-header .col {
        text-align: center;
        margin-top: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// ============================
// TOGGLE MOT DE PASSE
// ============================
function togglePwd(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// ============================
// APERÇU AVATAR (Page profil)
// ============================
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Vérification taille
        if (file.size > 2 * 1024 * 1024) {
            alert('L\'image ne doit pas dépasser 2 Mo.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
            document.getElementById('headerAvatar').src = e.target.result;
            document.getElementById('avatarSubmitSection').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function cancelAvatarChange() {
    document.getElementById('avatarInput').value = '';
    document.getElementById('avatarPreview').src = '{{ $user->avatar_url }}';
    document.getElementById('headerAvatar').src = '{{ $user->avatar_url }}';
    document.getElementById('avatarSubmitSection').style.display = 'none';
}

// Aperçu dans le modal
function previewModalAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('modalAvatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag & Drop Avatar
const avatarZone = document.getElementById('avatarUploadZone');
if (avatarZone) {
    avatarZone.addEventListener('click', () => document.getElementById('avatarInput').click());

    avatarZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        avatarZone.classList.add('drag-over');
    });

    avatarZone.addEventListener('dragleave', () => {
        avatarZone.classList.remove('drag-over');
    });

    avatarZone.addEventListener('drop', (e) => {
        e.preventDefault();
        avatarZone.classList.remove('drag-over');
        const input = document.getElementById('avatarInput');
        input.files = e.dataTransfer.files;
        previewAvatar(input);
    });
}

// ============================
// FORCE MOT DE PASSE (Profil)
// ============================
document.getElementById('new_password')?.addEventListener('input', function() {
    const pwd = this.value;
    let score = 0;
    const bar = document.getElementById('pwdStrengthBar');
    const text = document.getElementById('pwdStrengthText');

    const rules = {
        length: pwd.length >= 8,
        upper: /[A-Z]/.test(pwd),
        number: /[0-9]/.test(pwd),
        special: /[^A-Za-z0-9]/.test(pwd)
    };

    Object.entries(rules).forEach(([key, valid]) => {
        const el = document.getElementById('pwd_rule_' + key);
        if (valid) {
            el.classList.add('valid');
            el.querySelector('i').classList.replace('fa-circle', 'fa-check-circle');
            score++;
        } else {
            el.classList.remove('valid');
            el.querySelector('i').classList.replace('fa-check-circle', 'fa-circle');
        }
    });

    const percent = (score / 4) * 100;
    bar.style.width = percent + '%';

    if (score <= 1) {
        bar.className = 'progress-bar bg-danger';
        text.textContent = 'Faible';
        text.className = 'small text-danger';
    } else if (score === 2) {
        bar.className = 'progress-bar bg-warning';
        text.textContent = 'Moyen';
        text.className = 'small text-warning';
    } else if (score === 3) {
        bar.className = 'progress-bar bg-info';
        text.textContent = 'Bon';
        text.className = 'small text-info';
    } else {
        bar.className = 'progress-bar bg-success';
        text.textContent = 'Excellent !';
        text.className = 'small text-success fw-semibold';
    }

    checkPwdMatch();
});

// Vérification correspondance
document.getElementById('password_confirmation_profile')?.addEventListener('input', checkPwdMatch);

function checkPwdMatch() {
    const pwd = document.getElementById('new_password').value;
    const conf = document.getElementById('password_confirmation_profile').value;
    const msg = document.getElementById('pwdMatchMsg');

    if (!conf) { msg.textContent = ''; return; }

    if (pwd === conf) {
        msg.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>Les mots de passe correspondent';
        msg.className = 'small text-success d-block mt-1';
    } else {
        msg.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i>Les mots de passe ne correspondent pas';
        msg.className = 'small text-danger d-block mt-1';
    }
}

// ============================
// SPINNERS SOUMISSION
// ============================
document.getElementById('profileForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('saveInfoBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...';
});

document.getElementById('avatarForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('saveAvatarBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi...';
});

document.getElementById('passwordForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('savePwdBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Traitement...';
});

// ============================
// ACTIVER L'ONGLET CORRESPONDANT AUX ERREURS
// ============================
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->has('current_password') || $errors->has('password') || session('password_success'))
        new bootstrap.Tab(document.getElementById('password-tab')).show();
    @elseif($errors->has('avatar'))
        new bootstrap.Tab(document.getElementById('avatar-tab')).show();
    @endif
});
</script>
@endpush
