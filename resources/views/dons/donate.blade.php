@extends('layouts.app')

@section('title', 'Faire un Don')

@section('content')
<!-- Page Header -->
<section class="page-header bg-success text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-hand-holding-heart me-3"></i>Faire un Don
                </h1>
                <p class="lead mb-0">
                    Soutenez la valorisation des pratiques traditionnelles du Burkina Faso.
                    Votre générosité fait la différence !
                </p>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <i class="fas fa-hands-helping" style="font-size: 6rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Étapes de progression -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="steps-progress d-flex align-items-center justify-content-between">
                    <div class="step active" id="stepIndicator1">
                        <div class="step-circle">1</div>
                        <small class="step-label">Informations</small>
                    </div>
                    <div class="step-line"></div>
                    <div class="step" id="stepIndicator2">
                        <div class="step-circle">2</div>
                        <small class="step-label">Type de Don</small>
                    </div>
                    <div class="step-line"></div>
                    <div class="step" id="stepIndicator3">
                        <div class="step-circle">3</div>
                        <small class="step-label">Procédure</small>
                    </div>
                    <div class="step-line"></div>
                    <div class="step" id="stepIndicator4">
                        <div class="step-circle">4</div>
                        <small class="step-label">Confirmation</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Formulaire de Don -->
<section class="donation-form py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Messages Flash -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Formulaire Principal -->
                <form action="{{ route('donate.store') }}" method="POST" enctype="multipart/form-data" id="donationForm" novalidate>
                    @csrf

                    <!-- ======================= -->
                    <!-- ÉTAPE 1 : INFORMATIONS  -->
                    <!-- ======================= -->
                    <div class="step-content" id="step1">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-success text-white">
                                <h4 class="mb-0">
                                    <i class="fas fa-user me-2"></i>Étape 1 : Vos Informations
                                </h4>
                            </div>
                            <div class="card-body p-4">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="donor_name" class="form-label fw-semibold">
                                            Nom complet <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text"
                                                   class="form-control @error('donor_name') is-invalid @enderror"
                                                   id="donor_name"
                                                   name="donor_name"
                                                   value="{{ old('donor_name', auth()->user()->name ?? '') }}"
                                                   placeholder="Ex: Ouédraogo Aminata"
                                                   required>
                                        </div>
                                        @error('donor_name')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="donor_phone" class="form-label fw-semibold">
                                            Téléphone <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <span class="input-group-text">+226</span>
                                            <input type="tel"
                                                   class="form-control @error('donor_phone') is-invalid @enderror"
                                                   id="donor_phone"
                                                   name="donor_phone"
                                                   value="{{ old('donor_phone') }}"
                                                   placeholder="70 00 00 00"
                                                   pattern="[0-9]{8}"
                                                   required>
                                        </div>
                                        @error('donor_phone')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="donor_email" class="form-label fw-semibold">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email"
                                                   class="form-control @error('donor_email') is-invalid @enderror"
                                                   id="donor_email"
                                                   name="donor_email"
                                                   value="{{ old('donor_email', auth()->user()->email ?? '') }}"
                                                   placeholder="votre@email.com">
                                        </div>
                                        @error('donor_email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="donor_city" class="form-label fw-semibold">Ville</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            <select class="form-select @error('donor_city') is-invalid @enderror"
                                                    id="donor_city" name="donor_city">
                                                <option value="">-- Sélectionnez --</option>
                                                <option value="Ouagadougou" {{ old('donor_city') == 'Ouagadougou' ? 'selected' : '' }}>Ouagadougou</option>
                                                <option value="Bobo-Dioulasso" {{ old('donor_city') == 'Bobo-Dioulasso' ? 'selected' : '' }}>Bobo-Dioulasso</option>
                                                <option value="Koudougou" {{ old('donor_city') == 'Koudougou' ? 'selected' : '' }}>Koudougou</option>
                                                <option value="Ouahigouya" {{ old('donor_city') == 'Ouahigouya' ? 'selected' : '' }}>Ouahigouya</option>
                                                <option value="Banfora" {{ old('donor_city') == 'Banfora' ? 'selected' : '' }}>Banfora</option>
                                                <option value="Kaya" {{ old('donor_city') == 'Kaya' ? 'selected' : '' }}>Kaya</option>
                                                <option value="Dédougou" {{ old('donor_city') == 'Dédougou' ? 'selected' : '' }}>Dédougou</option>
                                                <option value="Tenkodogo" {{ old('donor_city') == 'Tenkodogo' ? 'selected' : '' }}>Tenkodogo</option>
                                                <option value="Fada N'Gourma" {{ old('donor_city') == "Fada N'Gourma" ? 'selected' : '' }}>Fada N'Gourma</option>
                                                <option value="Autre" {{ old('donor_city') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                            </select>
                                        </div>
                                        @error('donor_city')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_anonymous"
                                               name="is_anonymous"
                                               value="1"
                                               {{ old('is_anonymous') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_anonymous">
                                            <i class="fas fa-user-secret me-1"></i>
                                            Je souhaite rester anonyme
                                        </label>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="button" class="btn btn-success btn-lg" onclick="goToStep(2)">
                                        Suivant <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================= -->
                    <!-- ÉTAPE 2 : TYPE DE DON     -->
                    <!-- ========================= -->
                    <div class="step-content" id="step2" style="display: none;">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-success text-white">
                                <h4 class="mb-0">
                                    <i class="fas fa-gift me-2"></i>Étape 2 : Type de Don
                                </h4>
                            </div>
                            <div class="card-body p-4">

                                <p class="text-muted mb-4">Choisissez le type de don que vous souhaitez effectuer :</p>

                                <div class="row g-4 mb-4">
                                    <!-- Don Argent -->
                                    <div class="col-md-6">
                                        <div class="donation-type-card h-100 {{ old('donation_type', 'money') == 'money' ? 'selected' : '' }}"
                                             id="cardMoney" onclick="selectDonationType('money')">
                                            <input class="form-check-input d-none"
                                                   type="radio"
                                                   name="donation_type"
                                                   id="type_money"
                                                   value="money"
                                                   {{ old('donation_type', 'money') == 'money' ? 'checked' : '' }}
                                                   required>
                                            <div class="text-center p-4">
                                                <div class="donation-icon bg-success-subtle text-success rounded-circle mx-auto mb-3">
                                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                                </div>
                                                <h5 class="fw-bold">Don en Argent</h5>
                                                <p class="text-muted small mb-0">
                                                    Faites un don financier via Mobile Money, PayPal ou Carte Bancaire
                                                </p>
                                                <div class="mt-3">
                                                    <span class="badge bg-light text-dark me-1">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/Orange_logo.svg/24px-Orange_logo.svg.png" alt="Orange" height="14"> Orange Money
                                                    </span>
                                                    <span class="badge bg-light text-dark me-1">
                                                        <i class="fas fa-mobile-alt text-primary"></i> Moov Money
                                                    </span>
                                                    <span class="badge bg-light text-dark me-1">
                                                        <i class="fas fa-wave-square text-info"></i> Wave
                                                    </span>
                                                    <span class="badge bg-light text-dark me-1">
                                                        <i class="fab fa-paypal text-primary"></i> PayPal
                                                    </span>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-credit-card text-warning"></i> Carte
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Don Colis -->
                                    <div class="col-md-6">
                                        <div class="donation-type-card h-100 {{ old('donation_type') == 'package' ? 'selected' : '' }}"
                                             id="cardPackage" onclick="selectDonationType('package')">
                                            <input class="form-check-input d-none"
                                                   type="radio"
                                                   name="donation_type"
                                                   id="type_package"
                                                   value="package"
                                                   {{ old('donation_type') == 'package' ? 'checked' : '' }}>
                                            <div class="text-center p-4">
                                                <div class="donation-icon bg-warning-subtle text-warning rounded-circle mx-auto mb-3">
                                                    <i class="fas fa-box-open fa-2x"></i>
                                                </div>
                                                <h5 class="fw-bold">Don de Colis</h5>
                                                <p class="text-muted small mb-0">
                                                    Envoyez des articles, objets ou matériels à notre adresse
                                                </p>
                                                <div class="mt-3">
                                                    <span class="badge bg-light text-dark me-1">
                                                        <i class="fas fa-list-ul text-success"></i> Liste d'articles
                                                    </span>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-receipt text-danger"></i> Récépissé
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg" onclick="goToStep(1)">
                                        <i class="fas fa-arrow-left me-2"></i> Précédent
                                    </button>
                                    <button type="button" class="btn btn-success btn-lg" onclick="goToStep(3)">
                                        Suivant <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =============================== -->
                    <!-- ÉTAPE 3 : PROCÉDURE DE PAIEMENT -->
                    <!-- =============================== -->
                    <div class="step-content" id="step3" style="display: none;">

                        <!-- ===== SECTION ARGENT ===== -->
                        <div id="moneySection">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-success text-white">
                                    <h4 class="mb-0">
                                        <i class="fas fa-money-bill-wave me-2"></i>Étape 3 : Paiement - Don en Argent
                                    </h4>
                                </div>
                                <div class="card-body p-4">

                                    <!-- Montant -->
                                    <div class="mb-4">
                                        <label for="amount" class="form-label fw-semibold">
                                            Montant du don <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                            <input type="number"
                                                   class="form-control @error('amount') is-invalid @enderror"
                                                   id="amount"
                                                   name="amount"
                                                   min="100"
                                                   step="50"
                                                   value="{{ old('amount') }}"
                                                   placeholder="Entrez le montant">
                                            <span class="input-group-text fw-bold">FCFA</span>
                                        </div>
                                        @error('amount')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror

                                        <!-- Montants suggérés -->
                                        <div class="mt-2">
                                            <small class="text-muted me-2">Montants suggérés :</small>
                                            <button type="button" class="btn btn-outline-success btn-sm me-1 suggested-amount" data-amount="1000">1 000</button>
                                            <button type="button" class="btn btn-outline-success btn-sm me-1 suggested-amount" data-amount="2500">2 500</button>
                                            <button type="button" class="btn btn-outline-success btn-sm me-1 suggested-amount" data-amount="5000">5 000</button>
                                            <button type="button" class="btn btn-outline-success btn-sm me-1 suggested-amount" data-amount="10000">10 000</button>
                                            <button type="button" class="btn btn-outline-success btn-sm me-1 suggested-amount" data-amount="25000">25 000</button>
                                            <button type="button" class="btn btn-outline-success btn-sm suggested-amount" data-amount="50000">50 000</button>
                                        </div>
                                    </div>

                                    <!-- Choix du moyen de paiement -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            Moyen de paiement <span class="text-danger">*</span>
                                        </label>
                                        <div class="row g-3">
                                            <!-- Orange Money -->
                                            <div class="col-6 col-md-4">
                                                <div class="payment-method-card {{ old('payment_method') == 'orange_money' ? 'selected' : '' }}"
                                                     onclick="selectPaymentMethod('orange_money')">
                                                    <input class="d-none" type="radio" name="payment_method"
                                                           id="pm_orange" value="orange_money"
                                                           {{ old('payment_method') == 'orange_money' ? 'checked' : '' }}>
                                                    <div class="text-center">
                                                        <div class="payment-logo mb-2" style="background-color: #FF6600;">
                                                            <i class="fas fa-mobile-alt fa-lg text-white"></i>
                                                        </div>
                                                        <small class="fw-semibold d-block">Orange Money</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Moov Money -->
                                            <div class="col-6 col-md-4">
                                                <div class="payment-method-card {{ old('payment_method') == 'moov_money' ? 'selected' : '' }}"
                                                     onclick="selectPaymentMethod('moov_money')">
                                                    <input class="d-none" type="radio" name="payment_method"
                                                           id="pm_moov" value="moov_money"
                                                           {{ old('payment_method') == 'moov_money' ? 'checked' : '' }}>
                                                    <div class="text-center">
                                                        <div class="payment-logo mb-2" style="background-color: #0066CC;">
                                                            <i class="fas fa-mobile-alt fa-lg text-white"></i>
                                                        </div>
                                                        <small class="fw-semibold d-block">Moov Money</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Wave -->
                                            <div class="col-6 col-md-4">
                                                <div class="payment-method-card {{ old('payment_method') == 'wave' ? 'selected' : '' }}"
                                                     onclick="selectPaymentMethod('wave')">
                                                    <input class="d-none" type="radio" name="payment_method"
                                                           id="pm_wave" value="wave"
                                                           {{ old('payment_method') == 'wave' ? 'checked' : '' }}>
                                                    <div class="text-center">
                                                        <div class="payment-logo mb-2" style="background-color: #1DC3E0;">
                                                            <i class="fas fa-wave-square fa-lg text-white"></i>
                                                        </div>
                                                        <small class="fw-semibold d-block">Wave</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- PayPal -->
                                            <div class="col-6 col-md-6">
                                                <div class="payment-method-card {{ old('payment_method') == 'paypal' ? 'selected' : '' }}"
                                                     onclick="selectPaymentMethod('paypal')">
                                                    <input class="d-none" type="radio" name="payment_method"
                                                           id="pm_paypal" value="paypal"
                                                           {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                                                    <div class="text-center">
                                                        <div class="payment-logo mb-2" style="background-color: #003087;">
                                                            <i class="fab fa-paypal fa-lg text-white"></i>
                                                        </div>
                                                        <small class="fw-semibold d-block">PayPal</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Carte Bancaire -->
                                            <div class="col-6 col-md-6">
                                                <div class="payment-method-card {{ old('payment_method') == 'carte_bancaire' ? 'selected' : '' }}"
                                                     onclick="selectPaymentMethod('carte_bancaire')">
                                                    <input class="d-none" type="radio" name="payment_method"
                                                           id="pm_carte" value="carte_bancaire"
                                                           {{ old('payment_method') == 'carte_bancaire' ? 'checked' : '' }}>
                                                    <div class="text-center">
                                                        <div class="payment-logo mb-2" style="background-color: #1A1F71;">
                                                            <i class="fas fa-credit-card fa-lg text-white"></i>
                                                        </div>
                                                        <small class="fw-semibold d-block">Carte Bancaire</small>
                                                        <span class="badge bg-light text-muted" style="font-size: 0.65rem;">Visa / Mastercard</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('payment_method')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- ===== Procédures de paiement ===== -->

                                    <!-- Procédure Orange Money -->
                                    <div class="payment-procedure" id="proc_orange_money" style="display: none;">
                                        <div class="card border-warning bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title" style="color: #FF6600;">
                                                    <i class="fas fa-mobile-alt me-2"></i>Procédure Orange Money
                                                </h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <ol class="procedure-steps">
                                                            <li>Composez le <strong class="text-success">#144#</strong> sur votre téléphone Orange</li>
                                                            <li>Sélectionnez <strong>1. Transfert d'argent</strong></li>
                                                            <li>Entrez le numéro : <strong class="fs-5 text-danger">07 07 07 07</strong>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('07070707')">
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            </li>
                                                            <li>Entrez le montant : <strong id="orangeAmount">-- FCFA</strong></li>
                                                            <li>Confirmez avec votre <strong>code secret</strong></li>
                                                            <li>Notez le <strong>numéro de transaction</strong> reçu par SMS</li>
                                                        </ol>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <strong>Important :</strong> Entrez le numéro de transaction ci-dessous après avoir effectué le transfert.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <label class="form-label fw-semibold">
                                                        Numéro de transaction <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="transaction_number"
                                                               id="transaction_orange"
                                                               placeholder="Ex: MP240101.1234.A56789"
                                                               value="{{ old('transaction_number') }}">
                                                    </div>
                                                    <small class="text-muted">Le numéro reçu dans le SMS de confirmation Orange Money</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Procédure Moov Money -->
                                    <div class="payment-procedure" id="proc_moov_money" style="display: none;">
                                        <div class="card border-primary bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">
                                                    <i class="fas fa-mobile-alt me-2"></i>Procédure Moov Money
                                                </h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <ol class="procedure-steps">
                                                            <li>Composez le <strong class="text-success">*555#</strong> sur votre téléphone Moov</li>
                                                            <li>Sélectionnez <strong>1. Transfert</strong></li>
                                                            <li>Choisissez <strong>1. Vers un abonné Moov</strong></li>
                                                            <li>Entrez le numéro : <strong class="fs-5 text-danger">06 06 06 06</strong>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('06060606')">
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            </li>
                                                            <li>Entrez le montant : <strong id="moovAmount">-- FCFA</strong></li>
                                                            <li>Confirmez avec votre <strong>code PIN</strong></li>
                                                            <li>Conservez le <strong>numéro de transaction</strong> du SMS reçu</li>
                                                        </ol>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="alert alert-primary">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <strong>Important :</strong> Saisissez le numéro de transaction après le transfert.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <label class="form-label fw-semibold">
                                                        Numéro de transaction <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="transaction_number_moov"
                                                               id="transaction_moov"
                                                               placeholder="Ex: TXN20240101123456"
                                                               value="{{ old('transaction_number_moov') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Procédure Wave -->
                                    <div class="payment-procedure" id="proc_wave" style="display: none;">
                                        <div class="card border-info bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title text-info">
                                                    <i class="fas fa-wave-square me-2"></i>Procédure Wave
                                                </h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <ol class="procedure-steps">
                                                            <li>Ouvrez l'application <strong>Wave</strong> sur votre téléphone</li>
                                                            <li>Appuyez sur <strong>« Envoyer »</strong></li>
                                                            <li>Entrez le numéro : <strong class="fs-5 text-danger">05 05 05 05</strong>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('05050505')">
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            </li>
                                                            <li>Entrez le montant : <strong id="waveAmount">-- FCFA</strong></li>
                                                            <li>Vérifiez le nom du destinataire : <strong>TRADIPRATIC</strong></li>
                                                            <li>Confirmez le paiement</li>
                                                            <li>Faites une <strong>capture d'écran</strong> de la confirmation</li>
                                                        </ol>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="alert alert-info">
                                                            <i class="fas fa-camera me-2"></i>
                                                            <strong>Astuce :</strong> Prenez une capture d'écran et uploadez-la ci-dessous comme preuve.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">
                                                            ID de transaction Wave <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="transaction_number_wave"
                                                               id="transaction_wave"
                                                               placeholder="Ex: WAVE-XXXXX"
                                                               value="{{ old('transaction_number_wave') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Capture d'écran (optionnel)</label>
                                                        <input type="file"
                                                               class="form-control"
                                                               name="payment_screenshot"
                                                               accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Procédure PayPal -->
                                    <div class="payment-procedure" id="proc_paypal" style="display: none;">
                                        <div class="card border-primary bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title" style="color: #003087;">
                                                    <i class="fab fa-paypal me-2"></i>Procédure PayPal
                                                </h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <ol class="procedure-steps">
                                                            <li>Connectez-vous à votre compte <strong>PayPal</strong></li>
                                                            <li>Cliquez sur <strong>« Envoyer de l'argent »</strong></li>
                                                            <li>Entrez l'adresse : <strong class="text-danger">dons@tradipratic.bf</strong>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('dons@tradipratic.bf')">
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            </li>
                                                            <li>Entrez le montant en <strong>EUR ou USD</strong></li>
                                                            <li>Choisissez <strong>« Envoi entre proches »</strong> (sans frais)</li>
                                                            <li>Confirmez le paiement</li>
                                                            <li>Notez l'<strong>ID de transaction PayPal</strong></li>
                                                        </ol>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="alert alert-secondary">
                                                            <i class="fas fa-exchange-alt me-2"></i>
                                                            <strong>Conversion :</strong><br>
                                                            1 EUR ≈ 655 FCFA<br>
                                                            1 USD ≈ 600 FCFA
                                                        </div>
                                                        <div class="text-center mt-3">
                                                            <a href="https://www.paypal.com/paypalme/tradipratic"
                                                               target="_blank"
                                                               class="btn btn-primary">
                                                                <i class="fab fa-paypal me-2"></i>Payer via PayPal.me
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <label class="form-label fw-semibold">
                                                        ID de transaction PayPal <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fab fa-paypal"></i></span>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="transaction_number_paypal"
                                                               id="transaction_paypal"
                                                               placeholder="Ex: 5YJ28462HX123456K"
                                                               value="{{ old('transaction_number_paypal') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Procédure Carte Bancaire -->
                                    <div class="payment-procedure" id="proc_carte_bancaire" style="display: none;">
                                        <div class="card border-dark bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title text-dark">
                                                    <i class="fas fa-credit-card me-2"></i>Paiement par Carte Bancaire
                                                </h5>
                                                <hr>

                                                <div class="alert alert-info mb-4">
                                                    <i class="fas fa-shield-alt me-2"></i>
                                                    <strong>Paiement sécurisé :</strong>
                                                    Vos informations bancaires sont chiffrées et sécurisées. Nous acceptons Visa et Mastercard.
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label fw-semibold">
                                                            Numéro de carte <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="card_number"
                                                                   id="card_number"
                                                                   placeholder="0000 0000 0000 0000"
                                                                   maxlength="19"
                                                                   value="{{ old('card_number') }}"
                                                                   autocomplete="cc-number">
                                                            <span class="input-group-text">
                                                                <i class="fab fa-cc-visa text-primary me-1"></i>
                                                                <i class="fab fa-cc-mastercard text-warning"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-semibold">
                                                            Nom sur la carte <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="card_holder"
                                                               placeholder="OUEDRAOGO AMINATA"
                                                               value="{{ old('card_holder') }}"
                                                               autocomplete="cc-name">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label fw-semibold">
                                                            Expiration <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="card_expiry"
                                                               placeholder="MM/AA"
                                                               maxlength="5"
                                                               value="{{ old('card_expiry') }}"
                                                               autocomplete="cc-exp">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label fw-semibold">
                                                            CVV <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="input-group">
                                                            <input type="password"
                                                                   class="form-control"
                                                                   name="card_cvv"
                                                                   placeholder="***"
                                                                   maxlength="4"
                                                                   autocomplete="cc-csc">
                                                            <span class="input-group-text" data-bs-toggle="tooltip" title="3 chiffres au dos de votre carte">
                                                                <i class="fas fa-question-circle"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center mt-2">
                                                    <i class="fas fa-lock text-success me-2"></i>
                                                    <small class="text-muted">Connexion SSL 256 bits • Vos données ne sont pas stockées</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- ===== SECTION COLIS ===== -->
                        <div id="packageSection" style="display: none;">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-warning text-dark">
                                    <h4 class="mb-0">
                                        <i class="fas fa-box-open me-2"></i>Étape 3 : Don de Colis
                                    </h4>
                                </div>
                                <div class="card-body p-4">

                                    <!-- Adresse de livraison -->
                                    <div class="alert alert-success mb-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="alert-heading mb-1">
                                                    <i class="fas fa-map-marker-alt me-2"></i>Adresse de dépôt du colis
                                                </h6>
                                                <p class="mb-0">
                                                    <strong>TradiPratic - Siège</strong><br>
                                                    Secteur 30, Rue 15.45, Porte 256<br>
                                                    Ouagadougou, Burkina Faso<br>
                                                    Tél : +226 25 00 00 00
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <a href="https://maps.google.com/?q=Ouagadougou+Burkina+Faso" target="_blank" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-directions me-1"></i>Voir sur la carte
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Instructions -->
                                    <div class="alert alert-info mb-4">
                                        <h6><i class="fas fa-info-circle me-2"></i>Comment procéder ?</h6>
                                        <ol class="mb-0 small">
                                            <li>Préparez votre colis avec les articles que vous souhaitez donner</li>
                                            <li>Déposez-le à l'adresse ci-dessus <strong>du lundi au vendredi (8h-17h)</strong></li>
                                            <li>Vous recevrez un <strong>récépissé de dépôt</strong> au moment de la remise</li>
                                            <li>Remplissez la liste des articles ci-dessous et uploadez le récépissé</li>
                                        </ol>
                                    </div>

                                    <!-- Liste des articles du colis -->
                                    <h5 class="mb-3 pb-2 border-bottom">
                                        <i class="fas fa-list-ul me-2"></i>Liste des articles du colis
                                        <span class="text-danger">*</span>
                                    </h5>

                                    <div id="itemsList">
                                        <div class="item-row mb-3" id="itemRow_0">
                                            <div class="card border-light bg-light">
                                                <div class="card-body py-2 px-3">
                                                    <div class="row g-2 align-items-end">
                                                        <div class="col-md-4">
                                                            <label class="form-label small fw-semibold">Article</label>
                                                            <input type="text"
                                                                   class="form-control form-control-sm"
                                                                   name="items[0][name]"
                                                                   placeholder="Ex: Sac de mil"
                                                                   value="{{ old('items.0.name') }}"
                                                                   required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label small fw-semibold">Quantité</label>
                                                            <input type="number"
                                                                   class="form-control form-control-sm"
                                                                   name="items[0][quantity]"
                                                                   min="1"
                                                                   value="{{ old('items.0.quantity', 1) }}"
                                                                   required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label small fw-semibold">Unité</label>
                                                            <select class="form-select form-select-sm" name="items[0][unit]">
                                                                <option value="pièce">Pièce(s)</option>
                                                                <option value="kg">Kg</option>
                                                                <option value="sac">Sac(s)</option>
                                                                <option value="carton">Carton(s)</option>
                                                                <option value="lot">Lot(s)</option>
                                                                <option value="litre">Litre(s)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small fw-semibold">Description</label>
                                                            <input type="text"
                                                                   class="form-control form-control-sm"
                                                                   name="items[0][description]"
                                                                   placeholder="Détails..."
                                                                   value="{{ old('items.0.description') }}">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button type="button"
                                                                    class="btn btn-outline-danger btn-sm w-100 remove-item-btn"
                                                                    onclick="removeItem(this)"
                                                                    disabled
                                                                    title="Supprimer">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-outline-success btn-sm mb-4" onclick="addItem()">
                                        <i class="fas fa-plus me-2"></i>Ajouter un article
                                    </button>

                                    <div class="mb-3 text-end">
                                        <span class="badge bg-secondary" id="itemCount">1 article(s)</span>
                                    </div>

                                    <!-- Upload récépissé -->
                                    <h5 class="mb-3 pb-2 border-bottom">
                                        <i class="fas fa-receipt me-2"></i>Récépissé de dépôt
                                        <span class="text-danger">*</span>
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Photo/Scan du récépissé de dépôt <span class="text-danger">*</span>
                                        </label>
                                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('receipt_file').click()">
                                            <div class="text-center" id="uploadPlaceholder">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                <p class="mb-1">Cliquez pour uploader ou glissez votre fichier ici</p>
                                                <small class="text-muted">Formats acceptés : JPG, PNG, PDF — Max 5 Mo</small>
                                            </div>
                                            <div class="text-center d-none" id="uploadPreview">
                                                <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                                                <p class="mb-0 fw-semibold" id="uploadFileName"></p>
                                            </div>
                                        </div>
                                        <input type="file"
                                               class="d-none @error('receipt_file') is-invalid @enderror"
                                               id="receipt_file"
                                               name="receipt_file"
                                               accept=".jpg,.jpeg,.png,.pdf"
                                               onchange="handleFileUpload(this)">
                                        @error('receipt_file')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Numéro du récépissé</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="receipt_number"
                                                   placeholder="Ex: REC-2024-00123"
                                                   value="{{ old('receipt_number') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Date de dépôt</label>
                                            <input type="date"
                                                   class="form-control"
                                                   name="deposit_date"
                                                   value="{{ old('deposit_date', date('Y-m-d')) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-lg" onclick="goToStep(2)">
                                <i class="fas fa-arrow-left me-2"></i> Précédent
                            </button>
                            <button type="button" class="btn btn-success btn-lg" onclick="goToStep(4)">
                                Suivant <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ========================= -->
                    <!-- ÉTAPE 4 : CONFIRMATION    -->
                    <!-- ========================= -->
                    <div class="step-content" id="step4" style="display: none;">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-success text-white">
                                <h4 class="mb-0">
                                    <i class="fas fa-check-circle me-2"></i>Étape 4 : Confirmation
                                </h4>
                            </div>
                            <div class="card-body p-4">

                                <!-- Récapitulatif -->
                                <div class="card bg-light mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Récapitulatif de votre don</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="fw-semibold" style="width: 200px;">Donateur :</td>
                                                <td id="recap_name">--</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Téléphone :</td>
                                                <td id="recap_phone">--</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Email :</td>
                                                <td id="recap_email">--</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Type de don :</td>
                                                <td id="recap_type">--</td>
                                            </tr>
                                            <tr id="recap_amount_row">
                                                <td class="fw-semibold">Montant :</td>
                                                <td id="recap_amount" class="fs-5 text-success fw-bold">--</td>
                                            </tr>
                                            <tr id="recap_payment_row">
                                                <td class="fw-semibold">Moyen de paiement :</td>
                                                <td id="recap_payment">--</td>
                                            </tr>
                                            <tr id="recap_transaction_row">
                                                <td class="fw-semibold">Réf. transaction :</td>
                                                <td id="recap_transaction">--</td>
                                            </tr>
                                            <tr id="recap_items_row" style="display: none;">
                                                <td class="fw-semibold">Articles :</td>
                                                <td id="recap_items">--</td>
                                            </tr>
                                            <tr id="recap_receipt_row" style="display: none;">
                                                <td class="fw-semibold">Récépissé :</td>
                                                <td id="recap_receipt">--</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Message -->
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-semibold">
                                        <i class="fas fa-comment-alt me-2"></i>Message ou note (optionnel)
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="3"
                                              placeholder="Un mot pour accompagner votre don...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Conditions -->
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="accept_terms" name="accept_terms" required>
                                    <label class="form-check-label" for="accept_terms">
                                        Je confirme que les informations fournies sont exactes et j'accepte les
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">conditions d'utilisation</a>.
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>

                                <!-- Boutons -->
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary btn-lg" onclick="goToStep(3)">
                                        <i class="fas fa-arrow-left me-2"></i> Précédent
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                                        <i class="fas fa-heart me-2"></i>Confirmer mon Don
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <!-- ===== SIDEBAR ===== -->
            <div class="col-lg-4">

                <!-- Résumé en temps réel -->
                <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 100px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Votre Don</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Type :</span>
                            <span class="fw-semibold" id="sidebar_type">Argent</span>
                        </div>
                        <div id="sidebar_amount_section">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Montant :</span>
                                <span class="fw-bold text-success fs-5" id="sidebar_amount">0 FCFA</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Paiement :</span>
                                <span class="fw-semibold" id="sidebar_payment">Non sélectionné</span>
                            </div>
                        </div>
                        <div id="sidebar_items_section" style="display: none;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Articles :</span>
                                <span class="fw-semibold" id="sidebar_items_count">0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Récépissé :</span>
                                <span id="sidebar_receipt" class="text-danger"><i class="fas fa-times"></i> Non fourni</span>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Donateur :</span>
                            <span class="fw-semibold" id="sidebar_donor">--</span>
                        </div>
                    </div>
                </div>

                <!-- Pourquoi donner -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-question-circle text-success me-2"></i>Pourquoi donner ?</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-seedling text-success me-3 mt-1"></i>
                                <span>Préservez les pratiques agricoles traditionnelles</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-utensils text-success me-3 mt-1"></i>
                                <span>Valorisez le patrimoine culinaire burkinabè</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-hands-helping text-success me-3 mt-1"></i>
                                <span>Soutenez les communautés locales</span>
                            </li>
                            <li class="mb-0 d-flex align-items-start">
                                <i class="fas fa-graduation-cap text-success me-3 mt-1"></i>
                                <span>Formez la jeunesse aux savoir-faire ancestraux</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Sécurité -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-shield-alt text-success me-2"></i>Sécurité</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-2">
                            <i class="fas fa-lock text-success me-2 mt-1"></i>
                            <small>Connexion chiffrée SSL 256 bits</small>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <i class="fas fa-user-shield text-success me-2 mt-1"></i>
                            <small>Données personnelles protégées</small>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="fas fa-file-invoice text-success me-2 mt-1"></i>
                            <small>Reçu de don envoyé par email</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Conditions -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Conditions d'utilisation des dons</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>1. Utilisation des dons</h6>
                <p>Tous les dons reçus par TradiPratic sont utilisés exclusivement pour la valorisation des pratiques traditionnelles au Burkina Faso.</p>

                <h6>2. Transparence</h6>
                <p>Un rapport d'utilisation des dons est publié annuellement sur notre site web.</p>

                <h6>3. Protection des données</h6>
                <p>Les informations personnelles des donateurs sont protégées conformément à la législation en vigueur au Burkina Faso. Elles ne sont jamais partagées avec des tiers.</p>

                <h6>4. Reçu de don</h6>
                <p>Un reçu est automatiquement envoyé par email après validation du don.</p>

                <h6>5. Don anonyme</h6>
                <p>Si vous choisissez de rester anonyme, votre nom n'apparaîtra pas dans les publications, mais sera conservé dans nos registres internes.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">J'ai compris</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Stepper */
.steps-progress {
    position: relative;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    z-index: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1rem;
    transition: all 0.3s;
}

.step.active .step-circle,
.step.completed .step-circle {
    background: #198754;
    color: white;
}

.step.completed .step-circle::after {
    content: '✓';
    font-size: 1.2rem;
}

.step.completed .step-circle {
    font-size: 0;
}

.step-label {
    margin-top: 5px;
    color: #6c757d;
    font-weight: 500;
    white-space: nowrap;
}

.step.active .step-label {
    color: #198754;
    font-weight: 700;
}

.step-line {
    flex: 1;
    height: 3px;
    background: #e9ecef;
    margin: 0 10px;
    margin-bottom: 20px;
    transition: background 0.3s;
}

.step-line.active {
    background: #198754;
}

/* Donation Type Cards */
.donation-type-card {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.donation-type-card:hover {
    border-color: #198754;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.donation-type-card.selected {
    border-color: #198754;
    background: #f0fdf4;
    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
}

.donation-icon {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Payment Method Cards */
.payment-method-card {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 15px 10px;
    cursor: pointer;
    transition: all 0.3s;
    background: white;
}

.payment-method-card:hover {
    border-color: #198754;
    transform: translateY(-2px);
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.payment-method-card.selected {
    border-color: #198754;
    background: #f0fdf4;
    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
}

.payment-logo {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

/* Procedure Steps */
.procedure-steps li {
    margin-bottom: 12px;
    line-height: 1.6;
}

/* Upload Zone */
.upload-zone {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 40px 20px;
    cursor: pointer;
    transition: all 0.3s;
    background: #fafafa;
}

.upload-zone:hover {
    border-color: #198754;
    background: #f0fdf4;
}

.upload-zone.has-file {
    border-color: #198754;
    border-style: solid;
    background: #f0fdf4;
}

/* Responsive */
@media (max-width: 768px) {
    .step-label {
        font-size: 0.65rem;
    }

    .step-circle {
        width: 32px;
        height: 32px;
        font-size: 0.85rem;
    }

    .payment-method-card {
        padding: 10px 5px;
    }

    .payment-logo {
        width: 35px;
        height: 35px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// ============================
// VARIABLES GLOBALES
// ============================
let currentStep = 1;
let itemCounter = 1;

// ============================
// NAVIGATION ENTRE ÉTAPES
// ============================
function goToStep(step) {
    // Validation avant d'avancer
    if (step > currentStep) {
        if (!validateStep(currentStep)) return;
    }

    // Masquer toutes les étapes
    document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');

    // Afficher l'étape demandée
    document.getElementById('step' + step).style.display = 'block';

    // Mettre à jour les indicateurs
    updateStepIndicators(step);

    // Si étape 3, afficher la bonne section
    if (step === 3) {
        showCorrectSection();
    }

    // Si étape 4, remplir le récapitulatif
    if (step === 4) {
        fillRecap();
    }

    currentStep = step;

    // Scroll vers le haut
    window.scrollTo({ top: 300, behavior: 'smooth' });
}

function updateStepIndicators(step) {
    for (let i = 1; i <= 4; i++) {
        const indicator = document.getElementById('stepIndicator' + i);
        indicator.classList.remove('active', 'completed');

        if (i < step) {
            indicator.classList.add('completed');
        } else if (i === step) {
            indicator.classList.add('active');
        }
    }

    // Lignes de connexion
    document.querySelectorAll('.step-line').forEach((line, index) => {
        line.classList.toggle('active', index < step - 1);
    });
}

// ============================
// VALIDATION PAR ÉTAPE
// ============================
function validateStep(step) {
    if (step === 1) {
        const name = document.getElementById('donor_name').value.trim();
        const phone = document.getElementById('donor_phone').value.trim();

        if (!name) {
            showAlert('Veuillez entrer votre nom complet.', 'danger');
            document.getElementById('donor_name').focus();
            return false;
        }
        if (!phone || phone.length < 8) {
            showAlert('Veuillez entrer un numéro de téléphone valide (8 chiffres).', 'danger');
            document.getElementById('donor_phone').focus();
            return false;
        }
        return true;
    }

    if (step === 2) {
        const type = document.querySelector('input[name="donation_type"]:checked');
        if (!type) {
            showAlert('Veuillez sélectionner un type de don.', 'danger');
            return false;
        }
        return true;
    }

    if (step === 3) {
        const type = document.querySelector('input[name="donation_type"]:checked').value;

        if (type === 'money') {
            const amount = document.getElementById('amount').value;
            if (!amount || parseFloat(amount) < 100) {
                showAlert('Le montant minimum est de 100 FCFA.', 'danger');
                document.getElementById('amount').focus();
                return false;
            }

            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                showAlert('Veuillez sélectionner un moyen de paiement.', 'danger');
                return false;
            }

            // Vérifier le numéro de transaction selon le moyen
            const method = paymentMethod.value;
            let transField = null;

            if (method === 'orange_money') transField = document.getElementById('transaction_orange');
            else if (method === 'moov_money') transField = document.getElementById('transaction_moov');
            else if (method === 'wave') transField = document.getElementById('transaction_wave');
            else if (method === 'paypal') transField = document.getElementById('transaction_paypal');

            if (transField && !transField.value.trim() && method !== 'carte_bancaire') {
                showAlert('Veuillez entrer le numéro/ID de transaction.', 'danger');
                transField.focus();
                return false;
            }

            if (method === 'carte_bancaire') {
                const cardNum = document.getElementById('card_number').value.replace(/\s/g, '');
                if (!cardNum || cardNum.length < 13) {
                    showAlert('Veuillez entrer un numéro de carte valide.', 'danger');
                    return false;
                }
            }
        }

        if (type === 'package') {
            // Vérifier qu'au moins un article est rempli
            const firstItemName = document.querySelector('input[name="items[0][name]"]');
            if (!firstItemName || !firstItemName.value.trim()) {
                showAlert('Veuillez ajouter au moins un article dans la liste.', 'danger');
                firstItemName?.focus();
                return false;
            }

            // Vérifier le récépissé
            const receiptFile = document.getElementById('receipt_file');
            if (!receiptFile.files || !receiptFile.files.length) {
                showAlert('Veuillez uploader le récépissé de dépôt.', 'danger');
                return false;
            }
        }

        return true;
    }

    return true;
}

function showAlert(message, type) {
    // Supprimer les alertes existantes
    document.querySelectorAll('.dynamic-alert').forEach(el => el.remove());

    const currentStepEl = document.getElementById('step' + currentStep);
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show dynamic-alert`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'danger' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    currentStepEl.insertBefore(alertDiv, currentStepEl.firstChild);

    // Auto-supprimer après 5s
    setTimeout(() => alertDiv.remove(), 5000);
}

// ============================
// TYPE DE DON
// ============================
function selectDonationType(type) {
    document.querySelectorAll('.donation-type-card').forEach(card => card.classList.remove('selected'));
    document.getElementById(type === 'money' ? 'cardMoney' : 'cardPackage').classList.add('selected');
    document.getElementById(type === 'money' ? 'type_money' : 'type_package').checked = true;

    // Mettre à jour la sidebar
    document.getElementById('sidebar_type').textContent = type === 'money' ? 'Argent' : 'Colis';
    document.getElementById('sidebar_amount_section').style.display = type === 'money' ? 'block' : 'none';
    document.getElementById('sidebar_items_section').style.display = type === 'package' ? 'block' : 'none';
}

function showCorrectSection() {
    const type = document.querySelector('input[name="donation_type"]:checked')?.value || 'money';
    document.getElementById('moneySection').style.display = type === 'money' ? 'block' : 'none';
    document.getElementById('packageSection').style.display = type === 'package' ? 'block' : 'none';
}

// ============================
// MOYENS DE PAIEMENT
// ============================
function selectPaymentMethod(method) {
    // Mettre à jour l'UI
    document.querySelectorAll('.payment-method-card').forEach(card => card.classList.remove('selected'));
    event.currentTarget.closest('.payment-method-card').classList.add('selected');

    // Cocher le radio
    document.querySelector(`input[name="payment_method"][value="${method}"]`).checked = true;

    // Masquer toutes les procédures
    document.querySelectorAll('.payment-procedure').forEach(proc => proc.style.display = 'none');

    // Afficher la procédure correspondante
    const proc = document.getElementById('proc_' + method);
    if (proc) {
        proc.style.display = 'block';
        proc.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Mettre à jour les montants dans les procédures
    updateAmountsInProcedures();

    // Mettre à jour sidebar
    const methodNames = {
        'orange_money': 'Orange Money',
        'moov_money': 'Moov Money',
        'wave': 'Wave',
        'paypal': 'PayPal',
        'carte_bancaire': 'Carte Bancaire'
    };
    document.getElementById('sidebar_payment').textContent = methodNames[method] || method;
}

// ============================
// MONTANTS
// ============================
document.querySelectorAll('.suggested-amount').forEach(btn => {
    btn.addEventListener('click', function() {
        const amount = this.dataset.amount;
        document.getElementById('amount').value = amount;

        // Highlight bouton sélectionné
        document.querySelectorAll('.suggested-amount').forEach(b => b.classList.remove('btn-success', 'text-white'));
        this.classList.add('btn-success', 'text-white');

        updateAmountsInProcedures();
        updateSidebarAmount();
    });
});

document.getElementById('amount')?.addEventListener('input', function() {
    updateAmountsInProcedures();
    updateSidebarAmount();
    // Reset highlight boutons suggérés
    document.querySelectorAll('.suggested-amount').forEach(b => {
        b.classList.remove('btn-success', 'text-white');
        if (b.dataset.amount === this.value) {
            b.classList.add('btn-success', 'text-white');
        }
    });
});

function updateAmountsInProcedures() {
    const amount = document.getElementById('amount').value;
    const formatted = amount ? parseInt(amount).toLocaleString('fr-FR') + ' FCFA' : '-- FCFA';

    ['orangeAmount', 'moovAmount', 'waveAmount'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = formatted;
    });
}

function updateSidebarAmount() {
    const amount = document.getElementById('amount').value;
    const formatted = amount ? parseInt(amount).toLocaleString('fr-FR') + ' FCFA' : '0 FCFA';
    document.getElementById('sidebar_amount').textContent = formatted;
}

// ============================
// GESTION DES ARTICLES (COLIS)
// ============================
function addItem() {
    const itemsList = document.getElementById('itemsList');
    const newItem = document.createElement('div');
    newItem.className = 'item-row mb-3';
    newItem.id = `itemRow_${itemCounter}`;
    newItem.innerHTML = `
        <div class="card border-light bg-light">
            <div class="card-body py-2 px-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Article</label>
                        <input type="text"
                               class="form-control form-control-sm"
                               name="items[${itemCounter}][name]"
                               placeholder="Ex: Sac de riz"
                               required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Quantité</label>
                        <input type="number"
                               class="form-control form-control-sm"
                               name="items[${itemCounter}][quantity]"
                               min="1"
                               value="1"
                               required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Unité</label>
                        <select class="form-select form-select-sm" name="items[${itemCounter}][unit]">
                            <option value="pièce">Pièce(s)</option>
                            <option value="kg">Kg</option>
                            <option value="sac">Sac(s)</option>
                            <option value="carton">Carton(s)</option>
                            <option value="lot">Lot(s)</option>
                            <option value="litre">Litre(s)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <input type="text"
                               class="form-control form-control-sm"
                               name="items[${itemCounter}][description]"
                               placeholder="Détails...">
                    </div>
                    <div class="col-md-1">
                        <button type="button"
                                class="btn btn-outline-danger btn-sm w-100 remove-item-btn"
                                onclick="removeItem(this)"
                                title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    itemsList.appendChild(newItem);
    itemCounter++;
    updateItemCount();
    updateRemoveButtons();
}

function removeItem(button) {
    button.closest('.item-row').remove();
    updateItemCount();
    updateRemoveButtons();
}

function updateItemCount() {
    const count = document.querySelectorAll('.item-row').length;
    document.getElementById('itemCount').textContent = count + ' article(s)';
    document.getElementById('sidebar_items_count').textContent = count;
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach((row, index) => {
        const btn = row.querySelector('.remove-item-btn');
        if (btn) btn.disabled = rows.length <= 1;
    });
}

// ============================
// UPLOAD RÉCÉPISSÉ
// ============================
function handleFileUpload(input) {
    const uploadZone = document.getElementById('uploadZone');
    const placeholder = document.getElementById('uploadPlaceholder');
    const preview = document.getElementById('uploadPreview');
    const fileName = document.getElementById('uploadFileName');

    if (input.files && input.files.length > 0) {
        const file = input.files[0];

        // Vérifier la taille (5 Mo max)
        if (file.size > 5 * 1024 * 1024) {
            showAlert('Le fichier est trop volumineux. Taille maximale : 5 Mo.', 'danger');
            input.value = '';
            return;
        }

        uploadZone.classList.add('has-file');
        placeholder.classList.add('d-none');
        preview.classList.remove('d-none');
        fileName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' Mo)';

        // Sidebar
        document.getElementById('sidebar_receipt').innerHTML =
            '<i class="fas fa-check text-success"></i> ' + file.name;
    } else {
        uploadZone.classList.remove('has-file');
        placeholder.classList.remove('d-none');
        preview.classList.add('d-none');
        document.getElementById('sidebar_receipt').innerHTML =
            '<i class="fas fa-times text-danger"></i> Non fourni';
    }
}

// Drag & Drop
const uploadZone = document.getElementById('uploadZone');
if (uploadZone) {
    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('has-file');
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        if (!document.getElementById('receipt_file').files.length) {
            this.classList.remove('has-file');
        }
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        const input = document.getElementById('receipt_file');
        input.files = e.dataTransfer.files;
        handleFileUpload(input);
    });
}

// ============================
// RÉCAPITULATIF
// ============================
function fillRecap() {
    const isAnonymous = document.getElementById('is_anonymous').checked;
    const name = document.getElementById('donor_name').value;
    const phone = document.getElementById('donor_phone').value;
    const email = document.getElementById('donor_email').value;
    const type = document.querySelector('input[name="donation_type"]:checked')?.value;

    document.getElementById('recap_name').textContent = isAnonymous ? 'Anonyme' : name;
    document.getElementById('recap_phone').textContent = phone ? '+226 ' + phone : '--';
    document.getElementById('recap_email').textContent = email || 'Non renseigné';
    document.getElementById('recap_type').innerHTML = type === 'money'
        ? '<span class="badge bg-success">Don en Argent</span>'
        : '<span class="badge bg-warning text-dark">Don de Colis</span>';

    if (type === 'money') {
        const amount = document.getElementById('amount').value;
        document.getElementById('recap_amount').textContent =
            amount ? parseInt(amount).toLocaleString('fr-FR') + ' FCFA' : '--';

        const method = document.querySelector('input[name="payment_method"]:checked')?.value;
        const methodNames = {
            'orange_money': '<i class="fas fa-mobile-alt" style="color:#FF6600"></i> Orange Money',
            'moov_money': '<i class="fas fa-mobile-alt text-primary"></i> Moov Money',
            'wave': '<i class="fas fa-wave-square text-info"></i> Wave',
            'paypal': '<i class="fab fa-paypal text-primary"></i> PayPal',
            'carte_bancaire': '<i class="fas fa-credit-card"></i> Carte Bancaire'
        };
        document.getElementById('recap_payment').innerHTML = methodNames[method] || '--';

        // Transaction
        let transValue = '--';
        if (method === 'orange_money') transValue = document.getElementById('transaction_orange')?.value;
        else if (method === 'moov_money') transValue = document.getElementById('transaction_moov')?.value;
        else if (method === 'wave') transValue = document.getElementById('transaction_wave')?.value;
        else if (method === 'paypal') transValue = document.getElementById('transaction_paypal')?.value;
        else if (method === 'carte_bancaire') transValue = '•••• ' + (document.getElementById('card_number')?.value.slice(-4) || '****');

        document.getElementById('recap_transaction').textContent = transValue || '--';

        document.getElementById('recap_amount_row').style.display = '';
        document.getElementById('recap_payment_row').style.display = '';
        document.getElementById('recap_transaction_row').style.display = '';
        document.getElementById('recap_items_row').style.display = 'none';
        document.getElementById('recap_receipt_row').style.display = 'none';
    } else {
        // Colis
        let itemsHtml = '<ul class="list-unstyled mb-0">';
        document.querySelectorAll('.item-row').forEach(row => {
            const itemName = row.querySelector('input[name*="[name]"]')?.value;
            const itemQty = row.querySelector('input[name*="[quantity]"]')?.value;
            const itemUnit = row.querySelector('select[name*="[unit]"]')?.value;
            if (itemName) {
                itemsHtml += `<li><i class="fas fa-check text-success me-1"></i>${itemQty} ${itemUnit} - ${itemName}</li>`;
            }
        });
        itemsHtml += '</ul>';
        document.getElementById('recap_items').innerHTML = itemsHtml;

        const receiptFile = document.getElementById('receipt_file');
        document.getElementById('recap_receipt').innerHTML = receiptFile.files.length
            ? '<i class="fas fa-paperclip text-success me-1"></i>' + receiptFile.files[0].name
            : '<span class="text-danger">Non fourni</span>';

        document.getElementById('recap_amount_row').style.display = 'none';
        document.getElementById('recap_payment_row').style.display = 'none';
        document.getElementById('recap_transaction_row').style.display = 'none';
        document.getElementById('recap_items_row').style.display = '';
        document.getElementById('recap_receipt_row').style.display = '';
    }
}

// ============================
// SIDEBAR EN TEMPS RÉEL
// ============================
document.getElementById('donor_name')?.addEventListener('input', function() {
    const isAnonymous = document.getElementById('is_anonymous').checked;
    document.getElementById('sidebar_donor').textContent = isAnonymous ? 'Anonyme' : this.value || '--';
});

document.getElementById('is_anonymous')?.addEventListener('change', function() {
    const name = document.getElementById('donor_name').value;
    document.getElementById('sidebar_donor').textContent = this.checked ? 'Anonyme' : name || '--';
});

// ============================
// UTILITAIRES
// ============================
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Petit feedback
        const btn = event.currentTarget;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-success"></i>';
        btn.classList.add('btn-outline-success');
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-outline-success');
        }, 2000);
    });
}

// Formatage numéro de carte
document.getElementById('card_number')?.addEventListener('input', function() {
    let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/g, '');
    let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
    this.value = formatted;
});

// Formatage expiration
document.querySelector('input[name="card_expiry"]')?.addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
    }
    this.value = value;
});

// Soumission du formulaire
document.getElementById('donationForm')?.addEventListener('submit', function(e) {
    const terms = document.getElementById('accept_terms');
    if (!terms.checked) {
        e.preventDefault();
        showAlert('Veuillez accepter les conditions d\'utilisation.', 'danger');
        return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Traitement en cours...';
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Restaurer l'état si old() existe
    const oldType = '{{ old("donation_type", "money") }}';
    selectDonationType(oldType);

    const oldPayment = '{{ old("payment_method", "") }}';
    if (oldPayment) {
        selectPaymentMethod(oldPayment);
    }

    updateItemCount();
    updateRemoveButtons();
});
</script>
@endpush
