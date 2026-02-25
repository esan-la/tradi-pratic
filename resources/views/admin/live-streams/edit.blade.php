{{-- resources/views/admin/live-streams/create.blade.php --}}

@extends('layouts.admin')

@section('title', isset($liveStream) ? 'Modifier le Live' : 'Programmer un Live')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="fas fa-{{ isset($liveStream) ? 'edit' : 'plus-circle' }} text-success me-2"></i>
                        {{ isset($liveStream) ? 'Modifier le Live' : 'Programmer un Live' }}
                    </h2>
                </div>
                <a href="{{ route('admin.live-streams.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
            </div>

            <form action="{{ isset($liveStream) ? route('admin.live-streams.update', $liveStream) : route('admin.live-streams.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="liveForm">
                @csrf
                @if(isset($liveStream))
                    @method('PUT')
                @endif

                <!-- Informations générales -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle text-success me-2"></i>Informations générales</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                Titre de l'émission <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $liveStream->title ?? '') }}"
                                   placeholder="Ex: Prière du vendredi - Édition spéciale"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Décrivez le contenu de cette émission...">{{ old('description', $liveStream->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="category" class="form-label fw-semibold">Catégorie</label>
                                <select class="form-select @error('category') is-invalid @enderror"
                                        id="category" name="category">
                                    <option value="">-- Choisir --</option>
                                    @foreach($categories as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('category', $liveStream->category ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="scheduled_at" class="form-label fw-semibold">
                                    Date et heure <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local"
                                       class="form-control @error('scheduled_at') is-invalid @enderror"
                                       id="scheduled_at"
                                       name="scheduled_at"
                                       value="{{ old('scheduled_at', isset($liveStream) ? $liveStream->scheduled_at?->format('Y-m-d\TH:i') : '') }}"
                                       required>
                                @error('scheduled_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- YouTube -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fab fa-youtube text-danger me-2"></i>Configuration YouTube</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label for="youtube_url" class="form-label fw-semibold">
                                Lien YouTube (vidéo ou live)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-youtube text-danger"></i></span>
                                <input type="url"
                                       class="form-control @error('youtube_url') is-invalid @enderror"
                                       id="youtube_url"
                                       name="youtube_url"
                                       value="{{ old('youtube_url', $liveStream->youtube_url ?? '') }}"
                                       placeholder="https://www.youtube.com/watch?v=XXXXXXXXXXX">
                            </div>
                            @error('youtube_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="mt-2 d-flex align-items-center gap-2">
                                <small class="text-muted">ID détecté :</small>
                                <code id="detectedYoutubeId">{{ $liveStream->youtube_video_id ?? '--' }}</code>
                                <div id="youtubePreviewThumb" class="ms-2" style="display: none;">
                                    <img id="thumbPreview" src="" class="rounded" height="40">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info small">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Astuce :</strong> Créez d'abord votre live sur YouTube Studio, copiez le lien et collez-le ici.
                            L'ID sera extrait automatiquement. Formats acceptés :
                            <code>youtube.com/watch?v=</code>, <code>youtu.be/</code>, <code>youtube.com/live/</code>
                        </div>
                    </div>
                </div>

                <!-- Options -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-cog text-success me-2"></i>Options</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="thumbnail" class="form-label fw-semibold">
                                    Image de couverture
                                </label>
                                <input type="file"
                                       class="form-control @error('thumbnail') is-invalid @enderror"
                                       id="thumbnail"
                                       name="thumbnail"
                                       accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">JPG, PNG, WebP — Max 2 Mo — 1280×720 recommandé</small>
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if(isset($liveStream) && $liveStream->thumbnail)
                                    <div class="mt-2">
                                        <img src="{{ $liveStream->thumbnail_url }}"
                                             alt="Thumbnail actuel"
                                             class="rounded shadow-sm"
                                             style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold d-block">Options supplémentaires</label>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="chat_enabled"
                                           name="chat_enabled"
                                           value="1"
                                           {{ old('chat_enabled', $liveStream->chat_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chat_enabled">
                                        <i class="fas fa-comments text-info me-1"></i>
                                        Activer le chat en direct
                                    </label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_featured"
                                           name="is_featured"
                                           value="1"
                                           {{ old('is_featured', $liveStream->is_featured ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star text-warning me-1"></i>
                                        Mettre en avant (featured)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.live-streams.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-times me-1"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                        <i class="fas fa-save me-2"></i>
                        {{ isset($liveStream) ? 'Mettre à jour' : 'Programmer le Live' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Détection automatique de l'ID YouTube
document.getElementById('youtube_url')?.addEventListener('input', function() {
    const url = this.value;
    const idDisplay = document.getElementById('detectedYoutubeId');
    const thumbContainer = document.getElementById('youtubePreviewThumb');
    const thumbImg = document.getElementById('thumbPreview');

    const patterns = [
        /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/live\/)([a-zA-Z0-9_-]{11})/,
        /^([a-zA-Z0-9_-]{11})$/
    ];

    let videoId = null;
    for (const pattern of patterns) {
        const match = url.match(pattern);
        if (match) {
            videoId = match[1];
            break;
        }
    }

    if (videoId) {
        idDisplay.textContent = videoId;
        idDisplay.classList.add('text-success');
        thumbImg.src = `https://img.youtube.com/vi/${videoId}/mqdefault.jpg`;
        thumbContainer.style.display = 'inline-block';
    } else {
        idDisplay.textContent = url ? 'ID non détecté' : '--';
        idDisplay.classList.remove('text-success');
        thumbContainer.style.display = 'none';
    }
});

// Spinner soumission
document.getElementById('liveForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...';
});
</script>
@endpush
