{{-- resources/views/auth/reset-password.blade.php --}}

@extends('layouts.app')

@section('title', 'Nouveau mot de passe')

@section('content')
<section class="auth-section min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">

                <div class="text-center mb-4">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}"
                             alt="TradiPratic"
                             height="70"
                             class="mb-3"
                             onerror="this.style.display='none'">
                    </a>
                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-key fa-2x text-success"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold">Nouveau mot de passe</h2>
                    <p class="text-muted">Choisissez un mot de passe fort et sécurisé.</p>
                </div>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('password.update') }}" id="resetForm">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope text-success me-1"></i> Email
                                </label>
                                <input type="email"
                                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $email) }}"
                                       required
                                       readonly>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nouveau mot de passe -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock text-success me-1"></i> Nouveau mot de passe
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Minimum 8 caractères"
                                           required
                                           autofocus>
                                    <button class="input-group-text bg-light" type="button" onclick="togglePwd('password', 'icon1')">
                                        <i class="fas fa-eye text-muted" id="icon1"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror

                                <!-- Indicateur de force -->
                                <div class="mt-2">
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar" id="strengthBar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted" id="strengthText">Force du mot de passe</small>
                                </div>

                                <div class="mt-2">
                                    <small class="d-block" id="rule_length">
                                        <i class="fas fa-circle text-muted me-1" style="font-size: 0.5rem;"></i>
                                        Au moins 8 caractères
                                    </small>
                                    <small class="d-block" id="rule_upper">
                                        <i class="fas fa-circle text-muted me-1" style="font-size: 0.5rem;"></i>
                                        Une lettre majuscule
                                    </small>
                                    <small class="d-block" id="rule_number">
                                        <i class="fas fa-circle text-muted me-1" style="font-size: 0.5rem;"></i>
                                        Un chiffre
                                    </small>
                                    <small class="d-block" id="rule_special">
                                        <i class="fas fa-circle text-muted me-1" style="font-size: 0.5rem;"></i>
                                        Un caractère spécial (@, #, !, etc.)
                                    </small>
                                </div>
                            </div>

                            <!-- Confirmation -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="fas fa-lock text-success me-1"></i> Confirmer le mot de passe
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Retapez le mot de passe"
                                           required>
                                    <button class="input-group-text bg-light" type="button" onclick="togglePwd('password_confirmation', 'icon2')">
                                        <i class="fas fa-eye text-muted" id="icon2"></i>
                                    </button>
                                </div>
                                <small class="text-muted" id="matchMessage"></small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg fw-semibold" id="resetBtn">
                                    <i class="fas fa-save me-2"></i>Réinitialiser le mot de passe
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-success text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i> Retour à la connexion
                            </a>
                        </div>
                    </div>
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
}
.rule-valid {
    color: #198754 !important;
}
.rule-valid i {
    color: #198754 !important;
}
</style>
@endpush

@push('scripts')
<script>
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

// Vérification force du mot de passe
document.getElementById('password').addEventListener('input', function() {
    const pwd = this.value;
    let score = 0;
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');

    // Règles
    const rules = {
        length: pwd.length >= 8,
        upper: /[A-Z]/.test(pwd),
        number: /[0-9]/.test(pwd),
        special: /[^A-Za-z0-9]/.test(pwd)
    };

    Object.entries(rules).forEach(([key, valid]) => {
        const el = document.getElementById('rule_' + key);
        if (valid) {
            el.classList.add('rule-valid');
            el.querySelector('i').classList.replace('fa-circle', 'fa-check-circle');
            score++;
        } else {
            el.classList.remove('rule-valid');
            el.querySelector('i').classList.replace('fa-check-circle', 'fa-circle');
        }
    });

    // Barre de progression
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

    checkMatch();
});

// Vérification correspondance
document.getElementById('password_confirmation').addEventListener('input', checkMatch);

function checkMatch() {
    const pwd = document.getElementById('password').value;
    const conf = document.getElementById('password_confirmation').value;
    const msg = document.getElementById('matchMessage');

    if (!conf) {
        msg.textContent = '';
        return;
    }

    if (pwd === conf) {
        msg.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>Les mots de passe correspondent';
        msg.className = 'small text-success';
    } else {
        msg.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i>Les mots de passe ne correspondent pas';
        msg.className = 'small text-danger';
    }
}

document.getElementById('resetForm').addEventListener('submit', function() {
    const btn = document.getElementById('resetBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Traitement...';
});
</script>
@endpush
