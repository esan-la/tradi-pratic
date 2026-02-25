@extends('layouts.admin')

@section('title', 'Nouvelle Réalisation')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-2">Nouvelle Réalisation</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.realisations.index') }}">Réalisations</a></li>
                <li class="breadcrumb-item active">Créer</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.realisations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                                   value="{{ old('title') }}"
                                   required autofocus>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Résumé court -->
                        <div class="mb-3">
                            <label class="form-label">Résumé court</label>
                            <textarea name="short_description"
                                      class="form-control @error('short_description') is-invalid @enderror"
                                      rows="3"
                                      maxlength="500">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Max 500 caractères. Utilisé dans les aperçus et partages sociaux.</small>
                        </div>

                        <!-- Description complète avec TinyMCE -->
                        @include('components.tinymce', [
                            'name' => 'description',
                            'label' => 'Description complète',
                            'value' => old('description'),
                            'required' => true,
                            'help' => 'Description détaillée du projet (HTML supporté)'
                        ])

                        <!-- Catégorie -->
                        <div class="mb-3">
                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($categories as $key => $category)
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
                            <label class="form-label">Image principale <span class="text-danger">*</span></label>
                            <input type="file" name="image"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*"
                                   onchange="previewImage(this, 'imagePreview')"
                                   required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">JPG, PNG, WebP. Max 5MB. Résolution recommandée: 1200x800px</small>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <!-- Galerie -->
                        <div class="mb-3">
                            <label class="form-label">Galerie d'images (optionnel)</label>
                            <input type="file" name="gallery[]"
                                   class="form-control @error('gallery') is-invalid @enderror"
                                   accept="image/*"
                                   multiple
                                   onchange="previewGallery(this)">
                            @error('gallery')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Sélectionner plusieurs images. Max 10 images de 5MB chacune.</small>
                            <div id="galleryPreview" class="row g-2 mt-2"></div>
                        </div>

                        <!-- Vidéo YouTube/Vimeo -->
                        <div class="mb-3">
                            <label class="form-label">URL Vidéo YouTube/Vimeo (optionnel)</label>
                            <input type="url" name="video_url"
                                   class="form-control @error('video_url') is-invalid @enderror"
                                   value="{{ old('video_url') }}"
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
                <!-- Options de publication -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Publication</h6>
                    </div>
                    <div class="card-body">
                        <!-- Publié -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_published"
                                   id="isPublished" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPublished">
                                <i class="fas fa-eye me-1"></i> Publier immédiatement
                            </label>
                        </div>

                        <!-- Vedette -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured"
                                   id="isFeatured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isFeatured">
                                <i class="fas fa-star text-warning me-1"></i> Mettre en vedette
                            </label>
                            <small class="d-block text-muted">Apparaîtra en premier sur le site</small>
                        </div>

                        <!-- Ordre -->
                        <div class="mb-0">
                            <label class="form-label">Ordre d'affichage</label>
                            <input type="number" name="order"
                                   class="form-control"
                                   value="{{ old('order', 0) }}"
                                   min="0">
                            <small class="text-muted">Plus le nombre est petit, plus la réalisation apparaît en premier (0 = premier)</small>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer la réalisation
                            </button>
                            <a href="{{ route('admin.realisations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Aide -->
                <div class="card shadow mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="m-0"><i class="fas fa-info-circle me-2"></i>Aide</h6>
                    </div>
                    <div class="card-body">
                        <ul class="small mb-0 ps-3">
                            <li>L'image principale est obligatoire</li>
                            <li>Le slug est généré automatiquement</li>
                            <li>La galerie est optionnelle</li>
                            <li>Vous pouvez ajouter une vidéo YouTube/Vimeo</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Preview image principale
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
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-4';
                col.innerHTML = `
                    <img src="${e.target.result}"
                         class="img-thumbnail"
                         style="height: 120px; width: 100%; object-fit: cover;">
                    <small class="text-muted d-block">${file.name}</small>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endpush
