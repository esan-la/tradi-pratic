@extends('layouts.admin')

@section('title', isset($recipe) ? 'Modifier la recette' : 'Nouvelle recette')
@section('page-title', isset($recipe) ? 'Modifier la recette' : 'Nouvelle recette')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">{{ isset($recipe) ? 'Modifier' : 'Créer' }} une recette</h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($recipe) ? route('admin.recipes.update', $recipe) : route('admin.recipes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($recipe))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $recipe->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Portions</label>
                            <input type="number" name="servings" class="form-control @error('servings') is-invalid @enderror" value="{{ old('servings', $recipe->servings ?? '') }}" min="1">
                            @error('servings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description', $recipe->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Temps de préparation (minutes)</label>
                            <input type="number" name="prep_time" class="form-control @error('prep_time') is-invalid @enderror" value="{{ old('prep_time', $recipe->prep_time ?? '') }}" min="0">
                            @error('prep_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Temps de cuisson (minutes)</label>
                            <input type="number" name="cook_time" class="form-control @error('cook_time') is-invalid @enderror" value="{{ old('cook_time', $recipe->cook_time ?? '') }}" min="0">
                            @error('cook_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <!-- Ingredients -->
                        <div class="col-md-12">
                            <label class="form-label">Ingrédients <span class="text-danger">*</span></label>
                            <div id="ingredients-container">
                                @ --}}
                        <!-- Ingredients -->
                        <div class="col-md-12">
                            <label class="form-label">Ingrédients <span class="text-danger">*</span></label>
                            <div id="ingredients-container">
                                @if(isset($recipe) && $recipe->ingredients)
                                    @foreach($recipe->ingredients as $index => $ingredient)
                                        <div class="input-group mb-2 ingredient-item">
                                            <input type="text" name="ingredients[]" class="form-control" value="{{ $ingredient }}" placeholder="Ex: 500g de farine" required>
                                            <button type="button" class="btn btn-outline-danger remove-ingredient" onclick="removeItem(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 ingredient-item">
                                        <input type="text" name="ingredients[]" class="form-control" placeholder="Ex: 500g de farine" required>
                                        <button type="button" class="btn btn-outline-danger remove-ingredient" onclick="removeItem(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addIngredient()">
                                <i class="fas fa-plus me-1"></i>Ajouter un ingrédient
                            </button>
                            @error('ingredients')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Instructions -->
                        <div class="col-md-12">
                            <label class="form-label">Instructions <span class="text-danger">*</span></label>
                            <div id="instructions-container">
                                @if(isset($recipe) && $recipe->instructions)
                                    @foreach($recipe->instructions as $index => $instruction)
                                        <div class="input-group mb-2 instruction-item">
                                            <span class="input-group-text">{{ $index + 1 }}</span>
                                            <textarea name="instructions[]" class="form-control" rows="2" placeholder="Étape de préparation" required>{{ $instruction }}</textarea>
                                            <button type="button" class="btn btn-outline-danger" onclick="removeItem(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 instruction-item">
                                        <span class="input-group-text">1</span>
                                        <textarea name="instructions[]" class="form-control" rows="2" placeholder="Étape de préparation" required></textarea>
                                        <button type="button" class="btn btn-outline-danger" onclick="removeItem(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addInstruction()">
                                <i class="fas fa-plus me-1"></i>Ajouter une étape
                            </button>
                            @error('instructions')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="col-md-6">
                            <label class="form-label">Image de la recette</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(this)">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(isset($recipe) && $recipe->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $recipe->image) }}" alt="Image actuelle" id="image-preview" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @else
                                <div class="mt-2">
                                    <img id="image-preview" class="img-thumbnail" style="max-width: 200px; display: none;">
                                </div>
                            @endif
                        </div>

                        <!-- Video URL -->
                        <div class="col-md-6">
                            <label class="form-label">Lien vidéo YouTube (optionnel)</label>
                            <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url', $recipe->video_url ?? '') }}" placeholder="https://youtube.com/watch?v=...">
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Published Status -->
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $recipe->is_published ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Publier cette recette
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ isset($recipe) ? 'Mettre à jour' : 'Créer' }}
                        </button>
                        <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addIngredient() {
        const container = document.getElementById('ingredients-container');
        const newItem = document.createElement('div');
        newItem.className = 'input-group mb-2 ingredient-item';
        newItem.innerHTML = `
            <input type="text" name="ingredients[]" class="form-control" placeholder="Ex: 500g de farine" required>
            <button type="button" class="btn btn-outline-danger" onclick="removeItem(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newItem);
    }

    function addInstruction() {
        const container = document.getElementById('instructions-container');
        const count = container.querySelectorAll('.instruction-item').length + 1;
        const newItem = document.createElement('div');
        newItem.className = 'input-group mb-2 instruction-item';
        newItem.innerHTML = `
            <span class="input-group-text">${count}</span>
            <textarea name="instructions[]" class="form-control" rows="2" placeholder="Étape de préparation" required></textarea>
            <button type="button" class="btn btn-outline-danger" onclick="removeItem(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newItem);
        updateInstructionNumbers();
    }

    function removeItem(button) {
        const item = button.closest('.input-group');
        item.remove();
        updateInstructionNumbers();
    }

    function updateInstructionNumbers() {
        const instructions = document.querySelectorAll('#instructions-container .instruction-item');
        instructions.forEach((item, index) => {
            const numberSpan = item.querySelector('.input-group-text');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }

    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
