@extends('layouts.admin')

@section('title', 'Gestion des Médias')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Gestion des Médias</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Médias</li>
                </ol>
            </nav>
        </div>
        <div>
            @if(Auth::user()->hasPermission('media_images.create'))
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#uploadImagesModal">
                    <i class="fas fa-image"></i> Ajouter des Images
                </button>
            @endif
            @if(Auth::user()->hasPermission('media_videos.create'))
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addVideoModal">
                    <i class="fas fa-video"></i> Ajouter une Vidéo
                </button>
            @endif
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Images
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $images instanceof \Illuminate\Pagination\LengthAwarePaginator ? $images->total() : 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-images fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Vidéos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $videos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $videos->total() : 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-video fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Publiés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{
                                    ($images instanceof \Illuminate\Pagination\LengthAwarePaginator ? $images->where('is_published', true)->count() : 0) +
                                    ($videos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $videos->where('is_published', true)->count() : 0)
                                }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Non publiés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{
                                    ($images instanceof \Illuminate\Pagination\LengthAwarePaginator ? $images->where('is_published', false)->count() : 0) +
                                    ($videos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $videos->where('is_published', false)->count() : 0)
                                }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs corrigés -->
    <ul class="nav nav-tabs mb-4" id="mediaTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $type === 'all' ? 'active' : '' }}"
            href="{{ route('admin.media.index', ['type' => 'all']) }}">
                <i class="fas fa-th"></i> Tout
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $type === 'images' ? 'active' : '' }}"
            href="{{ route('admin.media.index', ['type' => 'images']) }}">
                <i class="fas fa-image"></i> Images
                ({{ $images instanceof \Illuminate\Pagination\LengthAwarePaginator ? $images->total() : 0 }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $type === 'videos' ? 'active' : '' }}"
            href="{{ route('admin.media.index', ['type' => 'videos']) }}">
                <i class="fas fa-video"></i> Vidéos
                ({{ $videos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $videos->total() : 0 }})
            </a>
        </li>
    </ul>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Images Section -->
    @if($type === 'all' || $type === 'images')
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-images"></i> Images
                </h6>
                @if(Auth::user()->hasPermission('media_images.delete'))
                    <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteImages" disabled>
                        <i class="fas fa-trash"></i> Supprimer la sélection
                    </button>
                @endif
            </div>
            <div class="card-body">
                @if($images->count() > 0)
                    <div class="row g-3">
                        @foreach($images as $image)
                            <div class="col-md-2 col-sm-3 col-4">
                                <div class="card image-card">
                                    @if(Auth::user()->hasPermission('media_images.delete'))
                                        <div class="form-check position-absolute top-0 start-0 m-2">
                                            <input class="form-check-input image-checkbox"
                                                   type="checkbox"
                                                   value="{{ $image->id }}"
                                                   id="img{{ $image->id }}">
                                        </div>
                                    @endif

                                    <img src="{{ $image->url }}"
                                         class="card-img-top"
                                         alt="Image"
                                         style="height: 150px; object-fit: cover; cursor: pointer;"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imageModal{{ $image->id }}">

                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-user"></i> {{ $image->user->name }}
                                            </small>

                                            <div class="btn-group btn-group-sm">
                                                @if(Auth::user()->hasPermission('media_images.edit'))
                                                    <form method="POST"
                                                          action="{{ route('admin.media.toggleImage', $image->id) }}"
                                                          class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-sm {{ $image->is_published ? 'btn-success' : 'btn-secondary' }}"
                                                                title="{{ $image->is_published ? 'Publié' : 'Non publié' }}">
                                                            <i class="fas fa-{{ $image->is_published ? 'eye' : 'eye-slash' }}"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(Auth::user()->hasPermission('media_images.delete'))
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete('image', {{ $image->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            {{ $image->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Modal -->
                            <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Image #{{ $image->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ $image->url }}" class="img-fluid" alt="Image">
                                            <div class="mt-3">
                                                <p><strong>Uploadé par:</strong> {{ $image->user->name }}</p>
                                                <p><strong>Date:</strong> {{ $image->created_at->format('d/m/Y H:i') }}</p>
                                                <p><strong>Statut:</strong>
                                                    <span class="badge bg-{{ $image->is_published ? 'success' : 'secondary' }}">
                                                        {{ $image->is_published ? 'Publié' : 'Non publié' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="{{ $image->url }}" class="btn btn-primary" target="_blank">
                                                <i class="fas fa-external-link-alt"></i> Voir l'original
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Fermer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $images->appends(['type' => $type])->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune image disponible</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Videos Section -->
    @if($type === 'all' || $type === 'videos')
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-video"></i> Vidéos
                </h6>
                @if(Auth::user()->hasPermission('media_videos.delete'))
                    <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteVideos" disabled>
                        <i class="fas fa-trash"></i> Supprimer la sélection
                    </button>
                @endif
            </div>
            <div class="card-body">
                @if($videos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    @can('media_videos.delete')
                                        <th width="30">
                                            <input type="checkbox" id="selectAllVideos">
                                        </th>
                                    @endcan
                                    <th>Aperçu</th>
                                    <th>URL</th>
                                    <th>Type</th>
                                    <th>Créé par</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($videos as $video)
                                    <tr>
                                        @if(Auth::user()->hasPermission('media_videos.delete'))
                                            <td>
                                                <input type="checkbox"
                                                       class="video-checkbox"
                                                       value="{{ $video->id }}">
                                            </td>
                                        @endif
                                        <td>
                                            <img src="{{ $video->thumbnail }}"
                                                 alt="Thumbnail"
                                                 style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                                 data-bs-toggle="modal"
                                                 data-bs-target="#videoModal{{ $video->id }}">
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($video->video_url, 50) }}</small>
                                        </td>
                                        <td>
                                            @if($video->is_youtube)
                                                <span class="badge bg-danger">
                                                    <i class="fab fa-youtube"></i> YouTube
                                                </span>
                                            @elseif($video->is_vimeo)
                                                <span class="badge bg-info">
                                                    <i class="fab fa-vimeo"></i> Vimeo
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-file-video"></i> Fichier
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $video->user->name }}</td>
                                        <td>
                                            <small>{{ $video->created_at->format('d/m/Y') }}</small><br>
                                            <small class="text-muted">{{ $video->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $video->is_published ? 'success' : 'secondary' }}">
                                                {{ $video->is_published ? 'Publié' : 'Non publié' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button"
                                                        class="btn btn-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#videoModal{{ $video->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                @if(Auth::user()->hasPermission('media_videos.edit'))
                                                    <form method="POST"
                                                          action="{{ route('admin.media.toggleVideo', $video->id) }}"
                                                          class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn {{ $video->is_published ? 'btn-success' : 'btn-secondary' }}">
                                                            <i class="fas fa-{{ $video->is_published ? 'eye' : 'eye-slash' }}"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(Auth::user()->hasPermission('media_videos.delete'))
                                                    <button type="button"
                                                            class="btn btn-danger"
                                                            onclick="confirmDelete('video', {{ $video->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Video Modal -->
                                    <div class="modal fade" id="videoModal{{ $video->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Vidéo #{{ $video->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($video->is_youtube || $video->is_vimeo)
                                                        <div class="ratio ratio-16x9">
                                                            <iframe src="{{ $video->embed_url }}"
                                                                    allowfullscreen
                                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                                                        </div>
                                                    @else
                                                        <video controls class="w-100">
                                                            <source src="{{ $video->url }}" type="video/mp4">
                                                        </video>
                                                    @endif

                                                    <div class="mt-3">
                                                        <p><strong>URL:</strong> {{ $video->video_url }}</p>
                                                        <p><strong>Uploadé par:</strong> {{ $video->user->name }}</p>
                                                        <p><strong>Date:</strong> {{ $video->created_at->format('d/m/Y H:i') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $videos->appends(['type' => $type])->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-video fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune vidéo disponible</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Modal Upload Images -->
@if(Auth::user()->hasPermission('media_images.create'))
<div class="modal fade" id="uploadImagesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-images"></i> Ajouter des Images
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.media.storeImages') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Vous pouvez sélectionner plusieurs images à la fois.
                        <br>Formats acceptés: JPG, PNG, GIF, WEBP. Taille max: 5MB par image.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Images <span class="text-danger">*</span></label>
                        <input type="file"
                               class="form-control"
                               name="images[]"
                               accept="image/*"
                               multiple
                               required
                               id="imageInput">
                        <div id="imagePreview" class="mt-3 row g-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Modal Add Video -->
@if(Auth::user()->hasPermission('media_videos.create'))
<div class="modal fade" id="addVideoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-video"></i> Ajouter une Vidéo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.media.storeVideo') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Collez l'URL complète de votre vidéo YouTube ou Vimeo.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL de la vidéo <span class="text-danger">*</span></label>
                        <input type="url"
                               class="form-control"
                               name="video_url"
                               placeholder="https://www.youtube.com/watch?v=..."
                               required>
                        <small class="text-muted">Formats supportés: YouTube, Vimeo</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Forms for delete actions -->
@if(Auth::user()->hasPermission('media_images.delete'))
    <form id="deleteImageForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endif

@if(Auth::user()->hasPermission('media_videos.delete'))
    <form id="deleteVideoForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endif

<style>
.image-card {
    transition: transform 0.2s;
    position: relative;
}
.image-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
</style>

@endsection

@push('scripts')
<script>
// Preview images before upload
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';

    const files = Array.from(e.target.files);
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML += `
                <div class="col-md-3">
                    <img src="${e.target.result}" class="img-fluid rounded" alt="Preview ${index + 1}">
                    <small class="text-muted d-block">${file.name}</small>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    });
});

// Confirm delete
function confirmDelete(type, id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
        const form = document.getElementById(`delete${type === 'image' ? 'Image' : 'Video'}Form`);
        form.action = `/admin/media/${type === 'image' ? 'images' : 'videos'}/${id}`;
        form.submit();
    }
}

// Bulk delete images
const imageCheckboxes = document.querySelectorAll('.image-checkbox');
const bulkDeleteImagesBtn = document.getElementById('bulkDeleteImages');

imageCheckboxes.forEach(cb => {
    cb.addEventListener('change', function() {
        const checked = Array.from(imageCheckboxes).filter(c => c.checked);
        if (bulkDeleteImagesBtn) {
            bulkDeleteImagesBtn.disabled = checked.length === 0;
        }
    });
});

if (bulkDeleteImagesBtn) {
    bulkDeleteImagesBtn.addEventListener('click', function() {
        const checked = Array.from(imageCheckboxes).filter(c => c.checked).map(c => c.value);

        if (checked.length > 0 && confirm(`Supprimer ${checked.length} image(s) ?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.media.bulkDeleteImages") }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            checked.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Bulk delete videos
const videoCheckboxes = document.querySelectorAll('.video-checkbox');
const bulkDeleteVideosBtn = document.getElementById('bulkDeleteVideos');
const selectAllVideos = document.getElementById('selectAllVideos');

if (selectAllVideos) {
    selectAllVideos.addEventListener('change', function() {
        videoCheckboxes.forEach(cb => cb.checked = this.checked);
        if (bulkDeleteVideosBtn) {
            bulkDeleteVideosBtn.disabled = !this.checked;
        }
    });
}

videoCheckboxes.forEach(cb => {
    cb.addEventListener('change', function() {
        const checked = Array.from(videoCheckboxes).filter(c => c.checked);
        if (bulkDeleteVideosBtn) {
            bulkDeleteVideosBtn.disabled = checked.length === 0;
        }
    });
});

if (bulkDeleteVideosBtn) {
    bulkDeleteVideosBtn.addEventListener('click', function() {
        const checked = Array.from(videoCheckboxes).filter(c => c.checked).map(c => c.value);

        if (checked.length > 0 && confirm(`Supprimer ${checked.length} vidéo(s) ?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.media.bulkDeleteVideos") }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            checked.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
