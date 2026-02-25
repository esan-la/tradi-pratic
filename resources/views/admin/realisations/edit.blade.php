@extends('layouts.admin')

@section('title', 'Modifier - ' . $realisation->title)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-2">Modifier la réalisation</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.realisations.index') }}">Réalisations</a></li>
                <li class="breadcrumb-item active">Modifier</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.realisations.update', $realisation) }}" method="POST" enctype="multipart/form-data">
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
                                   value="{{ old('title', $realisation->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Slug actuel: <code>{{ $realisation->slug }}</code></small>
                        </div>

                        <!-- Résumé court -->
                        <div class="mb-3">
                            <label class="form-label">Résumé court</label>
                            <textarea name="short_description"
                                      class="form-control @error('short_description') is-invalid @enderror"
                                      rows="3"
                                      maxlength="500">{{ old('short_description', $realisation->short_description) }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description avec TinyMCE -->
                        @include('components.tinymce', [
                            'name' => 'description',
                            'label' => 'Description complète',
                            'value' => old('description', $realisation->description),
                            'required' => true
                        ])

                        <!-- Catégorie -->
                        <div class="mb-3">
                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($categories as $key => $category)
                                    <option value="{{ $category }}" {{ old('category', $realisation->category) == $category ? 'selected' : '' }}>
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
                            <label class="form-label">Image principale</label>
                            @if($realisation->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $realisation->image) }}"
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
                        @if($realisation->gallery && count($realisation->gallery) > 0)
                            <div class="mb-3">
                                <label class="form-label">Galerie actuelle</label>
                                <div class="row g-2">
                                    @foreach($realisation->gallery as $index => $galleryImage)
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
                                                        Supprimer
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
                            <small class="text-muted">Les nouvelles images seront ajoutées à la galerie existante</small>
                            <div id="galleryPreview" class="row g-2 mt-2"></div>
                        </div>

                        <!-- Vidéo -->
                        <div class="mb-3">
                            <label class="form-label">URL Vidéo YouTube/Vimeo</label>
                            <input type="url" name="video_url"
                                   class="form-control @error('video_url') is-invalid @enderror"
                                   value="{{ old('video_url', $realisation->video_url) }}"
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
                                <strong>{{ $realisation->views ?? 0 }}</strong> vues
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                Créé le {{ $realisation->created_at->format('d/m/Y H:i') }}
                            </li>
                            <li>
                                <i class="fas fa-clock text-primary me-2"></i>
                                Modifié le {{ $realisation->updated_at->format('d/m/Y H:i') }}
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
                                   {{ old('is_published', $realisation->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPublished">
                                <i class="fas fa-eye me-1"></i> Publié
                            </label>
                        </div>

                        <!-- Vedette -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured"
                                   id="isFeatured" value="1"
                                   {{ old('is_featured', $realisation->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isFeatured">
                                <i class="fas fa-star text-warning me-1"></i> En vedette
                            </label>
                        </div>

                        <!-- Ordre -->
                        <div class="mb-0">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="order"
                                   class="form-control"
                                   value="{{ old('order', $realisation->order) }}"
                                   min="0">
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
                            <a href="{{ route('admin.realisations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            @can('realisations.view')
                                <a href="{{ route('realisations.show', $realisation->slug) }}"
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
</script>
@endpush
