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
                        <!-- Titre -->
                        <div class="col-md-8">
                            <label class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $realisation->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catégorie -->
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

                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $realisation->description ?? '') }}</textarea>
                            <small class="text-muted">Décrivez en détail votre réalisation</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image principale -->
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

                        <!-- Galerie d'images -->
                        <div class="col-md-12">
                            <label class="form-label">
                                <i class="fas fa-images me-1"></i>Galerie d'images (optionnel)
                            </label>
                            <input type="file" name="gallery[]" class="form-control @error('gallery.*') is-invalid @enderror" accept="image/*" multiple onchange="previewGalleryImages(this)">
                            <small class="text-muted">Vous pouvez sélectionner plusieurs images pour créer une galerie</small>
                            @error('gallery.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if(isset($realisation) && $realisation->gallery)
                                <div class="mt-3">
                                    <p class="mb-2"><strong>Galerie actuelle ({{ count($realisation->gallery) }} images):</strong></p>
                                    <div class="row g-2">
                                        @foreach($realisation->gallery as $galleryImage)
                                            <div class="col-md-2">
                                                <img src="{{ asset('storage/' . $galleryImage) }}" alt="Galerie" class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-info d-block mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Télécharger de nouvelles images remplacera la galerie actuelle
                                    </small>
                                </div>
                            @endif

                            <div id="gallery-preview" class="mt-3 row g-2" style="display: none;"></div>
                        </div>

                        <!-- Vidéo YouTube -->
                        <div class="col-md-12">
                            <label class="form-label">
                                <i class="fab fa-youtube text-danger me-1"></i>Lien vidéo YouTube (optionnel)
                            </label>
                            <input type="url"
                                   name="video_url"
                                   class="form-control @error('video_url') is-invalid @enderror"
                                   value="{{ old('video_url', $realisation->video_url ?? '') }}"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            <small class="text-muted">
                                Collez l'URL complète de votre vidéo YouTube (exemple: https://www.youtube.com/watch?v=dQw4w9WgXcQ)
                            </small>
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if(isset($realisation) && $realisation->video_url)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <p class="mb-2"><strong>Vidéo actuelle:</strong></p>
                                    <div class="ratio ratio-16x9" style="max-width: 400px;">
                                        <iframe
                                            src="{{ str_replace('watch?v=', 'embed/', $realisation->video_url) }}"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                    <a href="{{ $realisation->video_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fab fa-youtube me-1"></i>Voir sur YouTube
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Options de publication -->
                        <div class="col-md-12">
                            <hr class="my-4">
                            <h6 class="mb-3">Options de publication</h6>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $realisation->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    <i class="fas fa-star text-warning"></i> Mettre en vedette
                                </label>
                                <small class="d-block text-muted">Cette réalisation sera mise en avant sur la page d'accueil</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $realisation->is_published ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    <i class="fas fa-eye text-success"></i> Publier cette réalisation
                                </label>
                                <small class="d-block text-muted">La réalisation sera visible sur le site public</small>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ isset($realisation) ? 'Mettre à jour' : 'Créer' }}
                        </button>
                        <a href="{{ route('admin.realisations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>

                        @if(isset($realisation))
                            <a href="{{ route('realisations.show', $realisation->slug) }}" class="btn btn-info" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Aide -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Conseils
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>L'<strong>image principale</strong> est obligatoire et sera utilisée comme vignette</li>
                    <li>La <strong>galerie</strong> permet d'ajouter plusieurs photos supplémentaires</li>
                    <li>Le <strong>lien vidéo YouTube</strong> enrichit votre réalisation avec du contenu multimédia</li>
                    <li>Les réalisations <strong>en vedette</strong> sont mises en avant sur le site</li>
                    <li>Décochez <strong>"Publier"</strong> pour garder la réalisation en brouillon</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-check-label small {
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .ratio iframe {
        border: none;
        border-radius: 0.25rem;
    }

    #gallery-preview img,
    .img-thumbnail {
        transition: transform 0.3s ease;
    }

    #gallery-preview img:hover,
    .img-thumbnail:hover {
        transform: scale(1.05);
    }
</style>
@endpush

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

            // Afficher le nombre d'images sélectionnées
            const countBadge = document.createElement('div');
            countBadge.className = 'col-12 mb-2';
            countBadge.innerHTML = `<span class="badge bg-success"><i class="fas fa-images me-1"></i>${input.files.length} image(s) sélectionnée(s)</span>`;
            container.appendChild(countBadge);

            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-2';
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">
                            <span class="position-absolute top-0 end-0 m-1 badge bg-dark">${index + 1}</span>
                        </div>
                    `;
                    container.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    // Validation de l'URL YouTube
    document.addEventListener('DOMContentLoaded', function() {
        const videoUrlInput = document.querySelector('input[name="video_url"]');

        if (videoUrlInput) {
            videoUrlInput.addEventListener('blur', function() {
                const url = this.value.trim();

                if (url && !isValidYouTubeUrl(url)) {
                    this.classList.add('is-invalid');

                    // Ajouter un message d'erreur si pas déjà présent
                    if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Veuillez entrer une URL YouTube valide';
                        this.parentNode.insertBefore(errorDiv, this.nextElementSibling);
                    }
                } else {
                    this.classList.remove('is-invalid');
                    const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                    if (errorDiv) {
                        errorDiv.remove();
                    }
                }
            });
        }
    });

    function isValidYouTubeUrl(url) {
        const patterns = [
            /^(https?:\/\/)?(www\.)?youtube\.com\/watch\?v=[\w-]+/,
            /^(https?:\/\/)?(www\.)?youtu\.be\/[\w-]+/,
            /^(https?:\/\/)?(www\.)?youtube\.com\/embed\/[\w-]+/
        ];

        return patterns.some(pattern => pattern.test(url));
    }
</script>
@endpush
