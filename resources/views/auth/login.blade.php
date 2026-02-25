{{-- resources/views/auth/login.blade.php --}}

@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<section class="auth-section min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">

                <!-- Logo & Titre -->
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}"
                             alt="TradiPratic"
                             height="70"
                             class="mb-3"
                             onerror="this.style.display='none'">
                    </a>
                    <h2 class="fw-bold text-success">Connexion</h2>
                    <p class="text-muted">Accédez à votre espace TradiPratic</p>
                </div>

                <!-- Messages Flash -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Carte de Connexion -->
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
                            @csrf

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope text-success me-1"></i> Adresse Email
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-at text-muted"></i>
                                    </span>
                                    <input type="email"
                                           class="form-control border-start-0 @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="votre@email.com"
                                           required
                                           autofocus
                                           autocomplete="email">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Mot de passe -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="password" class="form-label fw-semibold mb-0">
                                        <i class="fas fa-lock text-success me-1"></i> Mot de passe
                                    </label>
                                    <a href="{{ route('password.request') }}"
                                       class="small text-success text-decoration-none">
                                        Mot de passe oublié ?
                                    </a>
                                </div>
                                <div class="input-group input-group-lg mt-2">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-key text-muted"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="••••••••"
                                           required
                                           autocomplete="current-password">
                                    <button class="input-group-text bg-light border-start-0"
                                            type="button"
                                            id="togglePassword"
                                            title="Afficher/Masquer">
                                        <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Se souvenir de moi -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="remember"
                                           name="remember"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Se souvenir de moi
                                    </label>
                                </div>
                            </div>

                            <!-- Bouton Connexion -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-success btn-lg fw-semibold" id="loginBtn">
                                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                </button>
                            </div>
                        </form>

                        <!-- Séparateur -->
                        <div class="text-center mt-4">
                            <div class="d-flex align-items-center">
                                <hr class="flex-grow-1">
                                <span class="px-3 text-muted small">Bienvenue sur TradiPratic</span>
                                <hr class="flex-grow-1">
                            </div>
                        </div>

                        <!-- Retour accueil -->
                        <div class="text-center mt-3">
                            <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i> Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Connexion sécurisée SSL • © {{ date('Y') }} TradiPratic
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.auth-section {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #f0f9ff 100%);
    min-height: 100vh;
}

.card {
    backdrop-filter: blur(10px);
}

.input-group-lg .form-control {
    font-size: 1rem;
}

.input-group-text {
    cursor: pointer;
}

.form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
}

.btn-success {
    background: linear-gradient(135deg, #198754, #157347);
    border: none;
    padding: 12px;
    transition: all 0.3s;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(25, 135, 84, 0.4);
}

/* Animation shake pour erreurs */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.is-invalid {
    animation: shake 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
// Toggle visibilité mot de passe
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Spinner sur soumission
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Connexion en cours...';
});
</script>
@endpush
