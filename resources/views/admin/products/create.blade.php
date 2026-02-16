@extends('layouts.admin')

@section('title', 'Créer un Produit')
@section('page-title', 'Nouveau Produit')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produits</a></li>
<li class="breadcrumb-item active">Créer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i>Informations du produit</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du produit <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               onkeyup="generateSlug(this.value)">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (URL) <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('slug') is-invalid @enderror"
                               id="slug"
                               name="slug"
                               value="{{ old('slug') }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Généré automatiquement si laissé vide</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Prix (FCFA) <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control @error('price') is-invalid @enderror"
                                   id="price"
                                   name="price"
                                   value="{{ old('price') }}"
                                   min="0"
                                   step="1"
                                   required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="compare_price" class="form-label">Prix de comparaison (FCFA)</label>
                            <input type="number"
                                   class="form-control @error('compare_price') is-invalid @enderror"
                                   id="compare_price"
                                   name="compare_price"
                                   value="{{ old('compare_price') }}"
                                   min="0"
                                   step="1">
                            @error('compare_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Prix barré (promotion)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                        <input type="number"
                               class="form-control @error('stock') is-invalid @enderror"
                               id="stock"
                               name="stock"
                               value="{{ old('stock', 0) }}"
                               min="0"
                               required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3"><i class="fas fa-image me-2"></i>Images du Produit</h6>

                    @include('partials.image-upload', [
                        'name' => 'image',
                        'label' => 'Image principale',
                        'required' => false,
                        'help' => 'Taille maximale: 10MB. L\'image sera stockée sans compression.'
                    ])

                    @include('partials.gallery-upload', [
                        'name' => 'gallery',
                        'label' => 'Galerie d\'images',
                        'help' => 'Sélectionnez plusieurs images pour la galerie du produit.'
                    ])

                    <hr class="my-4">

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="is_published"
                                   name="is_published"
                                   value="1"
                                   {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">
                                <strong>Publier le produit</strong>
                                <br><small class="text-muted">Le produit sera visible sur le site</small>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Créer le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Conseils :</strong>
                    <ul class="mb-0 mt-2">
                        <li>Utilisez des images de haute qualité</li>
                        <li>Le prix de comparaison permet d'afficher une promotion</li>
                        <li>Le slug doit être unique</li>
                        <li>Les images sont stockées sans compression</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>Gestion du Stock</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention :</strong>
                    <ul class="mb-0 mt-2">
                        <li><strong>Stock = 0</strong> : Produit en rupture</li>
                        <li><strong>Stock ≤ 10</strong> : Stock faible (alerte)</li>
                        <li>Le stock est déduit automatiquement lors des commandes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generateSlug(text) {
    const slug = text
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');

    document.getElementById('slug').value = slug;
}

// Preview price calculation
document.addEventListener('DOMContentLoaded', function() {
    const price = document.getElementById('price');
    const comparePrice = document.getElementById('compare_price');

    function updatePricePreview() {
        const priceValue = parseFloat(price.value) || 0;
        const comparePriceValue = parseFloat(comparePrice.value) || 0;

        if (comparePriceValue > priceValue) {
            const discount = Math.round(((comparePriceValue - priceValue) / comparePriceValue) * 100);
            comparePrice.classList.add('border-success');
            comparePrice.parentElement.querySelector('small').textContent =
                `Prix barré (Économie de ${discount}%)`;
        } else {
            comparePrice.classList.remove('border-success');
            comparePrice.parentElement.querySelector('small').textContent =
                'Prix barré (promotion)';
        }
    }

    if (price && comparePrice) {
        price.addEventListener('input', updatePricePreview);
        comparePrice.addEventListener('input', updatePricePreview);
    }
});
</script>
@endpush
