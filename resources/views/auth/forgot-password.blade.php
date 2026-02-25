{{-- resources/views/auth/forgot-password.blade.php --}}

@extends('layouts.app')

@section('title', 'Mot de passe oublié')

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
                    <div class="mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-unlock-alt fa-2x text-warning"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold">Mot de passe oublié ?</h2>
                    <p class="text-muted">
                        Pas de souci ! Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                    </p>
                </div>

                <!-- Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-paper-plane fa-lg me-3 mt-1 text-success"></i>
                            <div>
                                <strong>Email envoyé !</strong><br>
                                {{ session('success') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Formulaire -->
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                            @csrf

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
                                           autofocus>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-success btn-lg fw-semibold" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer le lien
                                </button>
                            </div>
                        </form>

                        <!-- Infos -->
                        <div class="bg-light rounded-3 p-3 mt-4">
                            <h6 class="mb-2"><i class="fas fa-info-circle text-info me-1"></i> Comment ça marche ?</h6>
                            <ol class="small text-muted mb-0">
                                <li class="mb-1">Entrez l'email de votre compte TradiPratic</li>
                                <li class="mb-1">Vous recevrez un email avec un lien sécurisé</li>
                                <li class="mb-1">Cliquez sur le lien (valable 60 minutes)</li>
                                <li>Choisissez un nouveau mot de passe</li>
                            </ol>
                        </div>

                        <!-- Retour connexion -->
                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-success text-decoration-none fw-semibold">
                                <i class="fas fa-arrow-left me-1"></i> Retour à la connexion
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        © {{ date('Y') }} TradiPratic • Connexion sécurisée
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
}

.form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
}

.btn-success {
    background: linear-gradient(135deg, #198754, #157347);
    border: none;
    padding: 12px;
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('forgotForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi en cours...';
});
</script>
@endpush
