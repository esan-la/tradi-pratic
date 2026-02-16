@extends('layouts.admin')

@section('title', 'Modifier la Publicité')
@section('page-title', 'Modifier la Publicité de Service')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.pub-services.index') }}">Publicités</a></li>
<li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Modifier le Service</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pub-services.update', $service) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="user_id" class="form-label">Prestataire <span class="text-danger">*</span></label>
                        <select class="form-select @error('user_id') is-invalid @enderror"
                                id="user_id"
                                name="user_id"
                                required>
                            <option value="">Sélectionner un prestataire</option>
                            @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $service->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Titre du service <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title', $service->title) }}"
                               required
                               onkeyup="generateSlug(this.value)">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (URL) <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('slug') is-invalid @enderror"
                               id="slug"
                               name="slug"
                               value="{{ old('slug', $service->slug) }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Doit être unique</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="5"
                                  required>{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Prix (FCFA)</label>
                        <input type="number"
                               class="form-control @error('price') is-invalid @enderror"
                               id="price"
                               name="price"
                               value="{{ old('price', $service->price) }}"
                               min="0"
                               step="1">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Laisser vide si le prix n'est pas applicable</small>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3"><i class="fas fa-image me-2"></i>Image du Service</h6>

                    @if($service->image)
                    <div class="mb-3">
                        <label class="form-label">Image actuelle</label>
                        <div class="position-relative d-inline-block">
                            <img src="{{ asset('storage/' . $service->image) }}"
                                 alt="{{ $service->title }}"
                                 class="img-thumbnail"
                                 style="max-width: 300px;">
                        </div>
                    </div>
                    @endif

                    @include('partials.image-upload', [
                        'name' => 'image',
                        'label' => $service->image ? 'Changer l\'image' : 'Image principale',
                        'required' => false,
                        'help' => 'Taille maximale: 10MB. Laisser vide pour conserver l\'image actuelle.'
                    ])

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_published"
                                       name="is_published"
                                       value="1"
                                       {{ old('is_published', $service->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    <strong>Publier</strong>
                                    <br><small class="text-muted">Le service sera visible sur le site</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_featured"
                                       name="is_featured"
                                       value="1"
                                       {{ old('is_featured', $service->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    <strong>Mettre en vedette</strong>
                                    <br><small class="text-muted">Affiché en priorité sur l'accueil</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.pub-services.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Enregistrer les Modifications
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
                <div class="mb-3">
                    <label class="text-muted small">Créé le</label>
                    <p class="mb-0"><strong>{{ $service->created_at->format('d/m/Y H:i') }}</strong></p>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Dernière modification</label>
                    <p class="mb-0"><strong>{{ $service->updated_at->format('d/m/Y H:i') }}</strong></p>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Statut actuel</label>
                    <p class="mb-0">
                        @if($service->is_published)
                            <span class="badge bg-success">Publié</span>
                        @else
                            <span class="badge bg-warning">En attente</span>
                        @endif
                    </p>
                </div>

                @if($service->is_featured)
                <div class="mb-0">
                    <label class="text-muted small">Vedette</label>
                    <p class="mb-0">
                        <span class="badge bg-info">
                            <i class="fas fa-star me-1"></i>Mis en vedette
                        </span>
                    </p>
                </div>
                @endif
            </div>
        </div>

        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Prestataire</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if($service->user->avatar)
                        <img src="{{ asset('storage/' . $service->user->avatar) }}"
                             class="rounded-circle me-2"
                             width="40" height="40">
                    @else
                        <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px;">
                            <span class="text-white fw-bold">{{ substr($service->user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <strong>{{ $service->user->name }}</strong><br>
                        <small class="text-muted">{{ $service->user->email }}</small>
                    </div>
                </div>

                <div class="d-grid">
                    <a href="{{ route('admin.users.show', $service->user) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i>Voir le Profil
                    </a>
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
</script>
@endpush
