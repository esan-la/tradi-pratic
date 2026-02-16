@extends('layouts.admin')

@section('title', 'Créer une Publicité')
@section('page-title', 'Nouvelle Publicité de Service')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.pub-services.index') }}">Publicités</a></li>
<li class="breadcrumb-item active">Créer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Informations du Service</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pub-services.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="user_id" class="form-label">Prestataire <span class="text-danger">*</span></label>
                        <select class="form-select @error('user_id') is-invalid @enderror"
                                id="user_id"
                                name="user_id"
                                required>
                            <option value="">Sélectionner un prestataire</option>
                            @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                               value="{{ old('title') }}"
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
                               value="{{ old('slug') }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Généré automatiquement si laissé vide</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="5"
                                  required>{{ old('description') }}</textarea>
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
                               value="{{ old('price') }}"
                               min="0"
                               step="1">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Laisser vide si le prix n'est pas applicable</small>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3"><i class="fas fa-image me-2"></i>Image du Service</h6>

                    @include('partials.image-upload', [
                        'name' => 'image',
                        'label' => 'Image principale',
                        'required' => true,
                        'help' => 'Taille maximale: 10MB. L\'image sera stockée sans compression.'
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
                                <strong>Publier immédiatement</strong>
                                <br><small class="text-muted">Le service sera visible sur le site</small>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.pub-services.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Créer la Publicité
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
                        <li>Utilisez un titre clair et accrocheur</li>
                        <li>Ajoutez une image de haute qualité</li>
                        <li>Décrivez précisément le service proposé</li>
                        <li>Le prix est optionnel (peut être "Sur devis")</li>
                        <li>Les images sont stockées sans compression</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Modération</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important :</strong>
                    <ul class="mb-0 mt-2">
                        <li><strong>Publié</strong> : Visible immédiatement sur le site</li>
                        <li><strong>Non publié</strong> : En attente de modération</li>
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
</script>
@endpush
