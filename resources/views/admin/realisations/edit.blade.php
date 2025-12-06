@extends('layouts.admin')

@section('title', isset($realisation) ? 'Modifier la réalisation' : 'Nouvelle réalisation')
@section('page-title', isset($realisation) ? 'Modifier la réalisation' : 'Nouvelle réalisation')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">{{ isset($realisation) ? 'Modifier' : 'Créer' }} une réalisation</h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($realisation) ? route('admin.realisations.update', $realisation) : route('admin.realisations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($realisation))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $realisation->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">Sélectionner...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category', $realisation->category ?? '') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $realisation->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Image principale <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewMainImage(this)" {{ isset($realisation) ? '' : 'required' }}>
                            <small class="text-muted">Format recommandé: JPG, PNG (Max: 2MB)</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if(isset($realisation) && $realisation->image)
                                <div class="mt-3">
                                    <p class="mb-2"><strong>Image actuelle:</strong></p>
                                    <img src="{{ asset('storage/' . $realisation->image) }}" alt="Image actuelle" id="main-image-preview" class="img-thumbnail" style="max-width: 300px;">
                                </div>
                            @else
                                <div class="mt-3">
                                    <img id="main-image-preview" class="img-thumbnail" style="max-width: 300px; display: none;">
                                </div>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Galerie d'images (optionnel)</label>
                            <input type="file" name="gallery[]" class="form-control @error('gallery.*') is-invalid @enderror" accept="image/*" multiple onchange="previewGalleryImages(this)">
                            <small class="text-muted">Vous pouvez sélectionner plusieurs images</small>
                            @error('gallery.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if(isset($realisation) && $realisation->gallery)
                                <div class="mt-3">
                                    <p class="mb-2"><strong>Galerie actuelle:</strong></p>
                                    <div class="row g-2">
                                        @foreach($realisation->gallery as $galleryImage)
                                            <div class="col-md-2">
                                                <img src="{{ asset('storage/' . $galleryImage) }}" alt="Galerie" class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div id="gallery-preview" class="mt-3 row g-2" style="display: none;"></div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $realisation->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    <i class="fas fa-star text-warning"></i> Mettre en vedette
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $realisation->is_published ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Publier cette réalisation
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ isset($realisation) ? 'Mettre à jour' : 'Créer' }}
                        </button>
                        <a href="{{ route('admin.realisations.index') }}" class="btn btn-secondary">
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
    function previewMainImage(input) {
        const preview = document.getElementById('main-image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGalleryImages(input) {
        const container = document.getElementById('gallery-preview');
        container.innerHTML = '';

        if (input.files && input.files.length > 0) {
            container.style.display = 'flex';

            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-2';
                    col.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">`;
                    container.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endpush
