@extends('layouts.admin')

@section('title', 'Modifier - ' . $recipe->title)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-2">Modifier la recette</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.recipes.index') }}">Recettes</a></li>
                <li class="breadcrumb-item active">Modifier</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations de base -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Informations principales</h6>
                    </div>
                    <div class="card-body">
                        <!-- Titre -->
                        <div class="mb-3">
                            <label class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $recipe->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Slug actuel: <code>{{ $recipe->slug }}</code></small>
                        </div>

                        <!-- Résumé court -->
                        <div class="mb-3">
                            <label class="form-label">Résumé court</label>
                            <textarea name="short_description"
                                      class="form-control @error('short_description') is-invalid @enderror"
                                      rows="2"
                                      maxlength="500">{{ old('short_description', $recipe->short_description) }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Max 500 caractères</small>
                        </div>

                        <!-- Description avec TinyMCE -->
                        @include('components.tinymce', [
                            'name' => 'description',
                            'label' => 'Description',
                            'value' => old('description', $recipe->description),
                            'required' => true
                        ])

                        <!-- Catégorie et Difficulté -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Catégorie</label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $category }}" {{ old('category', $recipe->category) == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Difficulté</label>
                                <select name="difficulty" class="form-select @error('difficulty') is-invalid @enderror">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($difficulties as $key => $difficulty)
                                        <option value="{{ $difficulty }}" {{ old('difficulty', $recipe->difficulty) == $difficulty ? 'selected' : '' }}>
                                            {{ $difficulty }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('difficulty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Temps et Portions -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class="far fa-clock text-primary"></i> Préparation (min)
                                </label>
                                <input type="number" name="prep_time" id="prep_time"
                                       class="form-control @error('prep_time') is-invalid @enderror"
                                       value="{{ old('prep_time', $recipe->prep_time) }}"
                                       min="0"
                                       onchange="updateTotalTime()">
                                @error('prep_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-fire text-danger"></i> Cuisson (min)
                                </label>
                                <input type="number" name="cook_time" id="cook_time"
                                       class="form-control @error('cook_time') is-invalid @enderror"
                                       value="{{ old('cook_time', $recipe->cook_time) }}"
                                       min="0"
                                       onchange="updateTotalTime()">
                                @error('cook_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-users text-success"></i> Portions
                                </label>
                                <input type="number" name="servings"
                                       class="form-control @error('servings') is-invalid @enderror"
                                       value="{{ old('servings', $recipe->servings) }}"
                                       min="1"
                                       placeholder="Nombre de personnes">
                                @error('servings')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Temps total -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Temps total : <strong id="total_time">{{ ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0) }} min</strong>
                        </div>
                    </div>
                </div>

                <!-- Ingrédients -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-shopping-basket me-2"></i>Ingrédients
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="ingredientsContainer">
                            @php
                                $ingredients = old('ingredients', $recipe->ingredients ?? []);
                            @endphp
                            @if(count($ingredients) > 0)
                                @foreach($ingredients as $ingredient)
                                    <div class="ingredient-item mb-2 d-flex gap-2">
                                        <input type="text" name="ingredients[]"
                                               class="form-control"
                                               value="{{ $ingredient }}"
                                               placeholder="Ex: 2 œufs"
                                               required>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="ingredient-item mb-2 d-flex gap-2">
                                    <input type="text" name="ingredients[]"
                                           class="form-control"
                                           placeholder="Ex: 2 œufs"
                                           required>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        @error('ingredients')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <button type="button" class="btn btn-success btn-sm mt-2" onclick="addIngredient()">
                            <i class="fas fa-plus me-2"></i>Ajouter un ingrédient
                        </button>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-list-ol me-2"></i>Instructions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="instructionsContainer">
                            @php
                                $instructions = old('instructions', $recipe->instructions ?? []);
                            @endphp
                            @if(count($instructions) > 0)
                                @foreach($instructions as $index => $instruction)
                                    <div class="instruction-item mb-3 d-flex gap-2">
                                        <span class="step-number fw-bold pt-2">{{ $index + 1 }}.</span>
                                        <textarea name="instructions[]"
                                                  class="form-control"
                                                  rows="2"
                                                  placeholder="Décrivez l'étape..."
                                                  required>{{ $instruction }}</textarea>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="instruction-item mb-3 d-flex gap-2">
                                    <span class="step-number fw-bold pt-2">1.</span>
                                    <textarea name="instructions[]"
                                              class="form-control"
                                              rows="2"
                                              placeholder="Décrivez l'étape..."
                                              required></textarea>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        @error('instructions')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <button type="button" class="btn btn-primary btn-sm mt-2" onclick="addInstruction()">
                            <i class="fas fa-plus me-2"></i>Ajouter une étape
                        </button>
                    </div>
                </div>

                <!-- Médias -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Médias</h6>
                    </div>
                    <div class="card-body">
                        <!-- Image principale -->
                        <div class="mb-3">
                            <label class="form-label">Image principale</label>
                            @if($recipe->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $recipe->image) }}"
                                         class="img-thumbnail"
                                         style="max-height: 200px;"
                                         alt="Image actuelle">
                                    <p class="small text-muted mt-1 mb-0">Image actuelle</p>
                                </div>
                            @endif
                            <input type="file" name="image"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*"
                                   onchange="previewImage(this, 'imagePreview')">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Laisser vide pour conserver l'image actuelle</small>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <!-- Galerie existante -->
                        @if($recipe->gallery && count($recipe->gallery) > 0)
                            <div class="mb-3">
                                <label class="form-label">Galerie actuelle</label>
                                <div class="row g-2">
                                    @foreach($recipe->gallery as $index => $galleryImage)
                                        <div class="col-md-3">
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $galleryImage) }}"
                                                     class="img-thumbnail"
                                                     style="height: 120px; width: 100%; object-fit: cover;">
                                                <div class="form-check position-absolute top-0 end-0 m-1 bg-white rounded">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           name="remove_gallery[]"
                                                           value="{{ $index }}"
                                                           id="remove{{ $index }}">
                                                    <label class="form-check-label small" for="remove{{ $index }}">
                                                        Suppr.
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted d-block mt-2">Cochez les images à supprimer</small>
                            </div>
                        @endif

                        <!-- Ajouter à la galerie -->
                        <div class="mb-3">
                            <label class="form-label">Ajouter des images à la galerie</label>
                            <input type="file" name="gallery[]"
                                   class="form-control @error('gallery') is-invalid @enderror"
                                   accept="image/*"
                                   multiple
                                   onchange="previewGallery(this)">
                            @error('gallery')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Les nouvelles images seront ajoutées</small>
                            <div id="galleryPreview" class="row g-2 mt-2"></div>
                        </div>

                        <!-- Vidéo -->
                        <div class="mb-3">
                            <label class="form-label">URL Vidéo YouTube/Vimeo</label>
                            <input type="url" name="video_url"
                                   class="form-control @error('video_url') is-invalid @enderror"
                                   value="{{ old('video_url', $recipe->video_url) }}"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statistiques -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-eye text-primary me-2"></i>
                                <strong>{{ $recipe->views ?? 0 }}</strong> vues
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                Créé le {{ $recipe->created_at->format('d/m/Y H:i') }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>
                                Modifié {{ $recipe->updated_at->diffForHumans() }}
                            </li>
                            <li>
                                <i class="fas fa-utensils text-primary me-2"></i>
                                {{ count($recipe->ingredients) }} ingrédients, {{ count($recipe->instructions) }} étapes
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Options -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Publication</h6>
                    </div>
                    <div class="card-body">
                        <!-- Publié -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_published"
                                   id="isPublished" value="1"
                                   {{ old('is_published', $recipe->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPublished">
                                <i class="fas fa-eye me-1"></i> Publié
                            </label>
                        </div>

                        <!-- Vedette -->
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_featured"
                                   id="isFeatured" value="1"
                                   {{ old('is_featured', $recipe->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isFeatured">
                                <i class="fas fa-star text-warning me-1"></i> En vedette
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                            <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            @can('recipes.view')
                                <a href="{{ route('recipes.show', $recipe->slug) }}"
                                   class="btn btn-info" target="_blank">
                                    <i class="fas fa-eye me-2"></i>Voir sur le site
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Preview image
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Preview galerie
function previewGallery(input) {
    const preview = document.getElementById('galleryPreview');
    preview.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-4';
                col.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="height: 120px; width: 100%; object-fit: cover;">`;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }
}

// Ajouter ingrédient
function addIngredient() {
    const item = document.createElement('div');
    item.className = 'ingredient-item mb-2 d-flex gap-2';
    item.innerHTML = `
        <input type="text" name="ingredients[]" class="form-control" placeholder="Ex: 100g de farine" required>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    document.getElementById('ingredientsContainer').appendChild(item);
}

// Ajouter instruction
function addInstruction() {
    const container = document.getElementById('instructionsContainer');
    const count = container.children.length + 1;
    const item = document.createElement('div');
    item.className = 'instruction-item mb-3 d-flex gap-2';
    item.innerHTML = `
        <span class="step-number fw-bold pt-2">${count}.</span>
        <textarea name="instructions[]" class="form-control" rows="2" placeholder="Décrivez l'étape..." required></textarea>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(item);
}

// Supprimer item
function removeItem(btn) {
    const parent = btn.closest('.ingredient-item, .instruction-item');
    parent.remove();
    updateStepNumbers();
}

// Mettre à jour numéros étapes
function updateStepNumbers() {
    document.querySelectorAll('#instructionsContainer .step-number').forEach((el, i) => {
        el.textContent = (i + 1) + '.';
    });
}

// Calculer temps total
function updateTotalTime() {
    const prep = parseInt(document.getElementById('prep_time').value) || 0;
    const cook = parseInt(document.getElementById('cook_time').value) || 0;
    document.getElementById('total_time').textContent = (prep + cook) + ' min';
}

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', function() {
    updateTotalTime();
});
</script>
@endpush
