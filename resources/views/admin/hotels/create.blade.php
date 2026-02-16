@extends('layouts.admin')

@section('title', 'Créer un Hôtel')
@section('page-title', 'Créer un Hôtel')
@section('page-description', 'Ajouter un nouvel hôtel à la plateforme')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.hotels.index') }}">Hôtels</a></li>
    <li class="breadcrumb-item active">Créer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-hotel me-2"></i>Informations de l'hôtel</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom de l'hôtel <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('address') is-invalid @enderror"
                                       id="address"
                                       name="address"
                                       value="{{ old('address') }}"
                                       required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="city" class="form-label">Ville <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('city') is-invalid @enderror"
                                       id="city"
                                       name="city"
                                       value="{{ old('city') }}"
                                       required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    @include('partials.image-upload', [
                        'name' => 'image',
                        'label' => 'Image principale',
                        'required' => false,
                        'help' => 'Taille maximale: 10MB. Formats: JPG, PNG, GIF. L\'image sera stockée sans compression.'
                    ])

                    @include('partials.gallery-upload', [
                        'name' => 'gallery',
                        'label' => 'Galerie d\'images',
                        'help' => 'Sélectionnez plusieurs images pour la galerie. Stockage sans compression.'
                    ])

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Conseils :</strong>
                    <ul class="mb-0 mt-2">
                        <li>Utilisez un nom clair et descriptif</li>
                        <li>Ajoutez des images de haute qualité</li>
                        <li>Fournissez une description détaillée</li>
                        <li>Les images sont stockées sans compression</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Remarque :</strong>
                    Après création, vous pourrez ajouter des chambres à cet hôtel.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
