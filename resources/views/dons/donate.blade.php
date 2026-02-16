@extends('layouts.app')

@section('title', 'Faire un Don')

@section('content')
<!-- Page Header -->
<section class="page-header bg-success text-white py-5">
    <div class="container">
        <h1 class="display-4 mb-3">Faire un Don</h1>
        <p class="lead">Votre générosité fait la différence</p>
    </div>
</section>

<!-- Donation Form -->
<section class="donation-form py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Info Card -->
                <div class="alert alert-info mb-4">
                    <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Information</h5>
                    <p class="mb-0">
                        Vos dons nous aident à continuer notre mission. Vous pouvez faire un don en argent,
                        par chèque, ou contribuer avec des objets et des colis. Merci pour votre générosité !
                    </p>
                </div>

                <!-- Donation Form -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-hand-holding-heart me-2"></i>Formulaire de Don</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('donate.store') }}" method="POST" id="donationForm">
                            @csrf

                            <!-- Section Donateur -->
                            <h5 class="mb-3 pb-2 border-bottom">Vos Informations</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="donor_name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('donor_name') is-invalid @enderror"
                                           id="donor_name"
                                           name="donor_name"
                                           value="{{ old('donor_name') }}"
                                           required>
                                    @error('donor_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="donor_phone" class="form-label">Téléphone</label>
                                    <input type="tel"
                                           class="form-control @error('donor_phone') is-invalid @enderror"
                                           id="donor_phone"
                                           name="donor_phone"
                                           value="{{ old('donor_phone') }}">
                                    @error('donor_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="donor_email" class="form-label">Email</label>
                                    <input type="email"
                                           class="form-control @error('donor_email') is-invalid @enderror"
                                           id="donor_email"
                                           name="donor_email"
                                           value="{{ old('donor_email') }}">
                                    @error('donor_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="donor_address" class="form-label">Adresse</label>
                                    <input type="text"
                                           class="form-control @error('donor_address') is-invalid @enderror"
                                           id="donor_address"
                                           name="donor_address"
                                           value="{{ old('donor_address') }}">
                                    @error('donor_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_anonymous"
                                           name="is_anonymous"
                                           value="1"
                                           {{ old('is_anonymous') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_anonymous">
                                        Je souhaite rester anonyme
                                    </label>
                                </div>
                            </div>

                            <!-- Section Type de Don -->
                            <h5 class="mb-3 pb-2 border-bottom">Type de Don</h5>

                            <div class="mb-4">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="form-check form-check-card">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="donation_type"
                                                   id="type_money"
                                                   value="money"
                                                   {{ old('donation_type', 'money') == 'money' ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label" for="type_money">
                                                <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                                                <div>Argent</div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-check-card">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="donation_type"
                                                   id="type_cheque"
                                                   value="cheque"
                                                   {{ old('donation_type') == 'cheque' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type_cheque">
                                                <i class="fas fa-credit-card fa-2x text-success mb-2"></i>
                                                <div>Chèque</div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-check-card">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="donation_type"
                                                   id="type_object"
                                                   value="object"
                                                   {{ old('donation_type') == 'object' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type_object">
                                                <i class="fas fa-gift fa-2x text-success mb-2"></i>
                                                <div>Objets</div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-check-card">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="donation_type"
                                                   id="type_package"
                                                   value="package"
                                                   {{ old('donation_type') == 'package' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type_package">
                                                <i class="fas fa-box fa-2x text-success mb-2"></i>
                                                <div>Colis</div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Montant (pour argent et chèque) -->
                            <div id="amountSection" class="mb-4">
                                <h5 class="mb-3 pb-2 border-bottom">Montant</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="amount" class="form-label">Montant <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number"
                                                   class="form-control @error('amount') is-invalid @enderror"
                                                   id="amount"
                                                   name="amount"
                                                   min="0"
                                                   step="0.01"
                                                   value="{{ old('amount') }}">
                                            <span class="input-group-text">FCFA</span>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Items (pour objets et colis) -->
                            <div id="itemsSection" class="mb-4" style="display: none;">
                                <h5 class="mb-3 pb-2 border-bottom">Liste des Articles</h5>
                                <div id="itemsList">
                                    <div class="item-row mb-3">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input type="text"
                                                       class="form-control"
                                                       name="items[0][name]"
                                                       placeholder="Nom de l'article">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number"
                                                       class="form-control"
                                                       name="items[0][quantity]"
                                                       placeholder="Quantité"
                                                       min="1"
                                                       value="1">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text"
                                                       class="form-control"
                                                       name="items[0][description]"
                                                       placeholder="Description (optionnel)">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeItem(this)" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="addItem()">
                                    <i class="fas fa-plus me-2"></i>Ajouter un article
                                </button>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label">Message ou Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-heart me-2"></i>Faire un Don
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Pourquoi donner ?</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Soutenez notre mission communautaire
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Aidez les personnes dans le besoin
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Contribuez au développement local
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Faites une différence concrète
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Sécurité</h5>
                    </div>
                    <div class="card-body">
                        <p class="small mb-0">
                            Vos informations personnelles sont protégées et ne seront jamais partagées
                            avec des tiers. Tous les dons sont enregistrés de manière sécurisée.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.form-check-card {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    height: 100%;
}

.form-check-card:hover {
    border-color: #28a745;
    background: #f8f9fa;
}

.form-check-card input[type="radio"] {
    display: none;
}

.form-check-card input[type="radio"]:checked + label {
    background: #28a745;
    color: white;
    border-radius: 8px;
    padding: 1rem;
}

.form-check-card input[type="radio"]:checked + label i {
    color: white !important;
}

.form-check-card label {
    margin: 0;
    cursor: pointer;
    display: block;
    transition: all 0.3s;
}
</style>
@endpush

@push('scripts')
<script>
let itemCounter = 1;

// Gérer l'affichage des sections selon le type de don
document.querySelectorAll('input[name="donation_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const amountSection = document.getElementById('amountSection');
        const itemsSection = document.getElementById('itemsSection');
        const amountInput = document.getElementById('amount');

        if (this.value === 'money' || this.value === 'cheque') {
            amountSection.style.display = 'block';
            itemsSection.style.display = 'none';
            amountInput.required = true;
        } else {
            amountSection.style.display = 'none';
            itemsSection.style.display = 'block';
            amountInput.required = false;
        }
    });
});

// Ajouter un article
function addItem() {
    const itemsList = document.getElementById('itemsList');
    const newItem = document.createElement('div');
    newItem.className = 'item-row mb-3';
    newItem.innerHTML = `
        <div class="row">
            <div class="col-md-5">
                <input type="text" class="form-control" name="items[${itemCounter}][name]" placeholder="Nom de l'article">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="items[${itemCounter}][quantity]" placeholder="Quantité" min="1" value="1">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="items[${itemCounter}][description]" placeholder="Description (optionnel)">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeItem(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    itemsList.appendChild(newItem);
    itemCounter++;
}

// Supprimer un article
function removeItem(button) {
    button.closest('.item-row').remove();
}
</script>
@endpush
