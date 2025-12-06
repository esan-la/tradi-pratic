@extends('layouts.admin')

@section('title', 'Détails de la réalisation')
@section('page-title', $realisation->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <img src="{{ asset('storage/' . $realisation->image) }}" class="card-img-top" alt="{{ $realisation->title }}" style="max-height: 400px; object-fit: cover;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-primary mb-2">{{ $realisation->category }}</span>
                        <h3>{{ $realisation->title }}</h3>
                    </div>
                    <div>
                        @if($realisation->is_featured)
                            <span class="badge bg-warning me-1"><i class="fas fa-star"></i> Vedette</span>
                        @endif
                        @if($realisation->is_published)
                            <span class="badge bg-success">Publiée</span>
                        @else
                            <span class="badge bg-secondary">Brouillon</span>
                        @endif
                    </div>
                </div>

                <p class="lead">{{ $realisation->description }}</p>

                @if($realisation->gallery && count($realisation->gallery) > 0)
                <div class="mt-4">
                    <h5 class="mb-3">Galerie</h5>
                    <div class="row g-3">
                        @foreach($realisation->gallery as $image)
                            <div class="col-md-4">
                                <img src="{{ asset('storage/' . $image) }}" alt="Galerie" class="img-thumbnail" style="width: 100%; height: 200px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <p><strong>Catégorie:</strong><br>{{ $realisation->category }}</p>
                <p><strong>Créée le:</strong><br>{{ $realisation->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Modifiée le:</strong><br>{{ $realisation->updated_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Slug:</strong><br><small class="text-muted">{{ $realisation->slug }}</small></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.realisations.edit', $realisation) }}" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
                <a href="{{ route('realisations.show', $realisation->slug) }}" class="btn btn-info w-100 mb-2" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                </a>
                <a href="{{ route('admin.realisations.index') }}" class="btn btn-secondary w-100 mb-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <form action="{{ route('admin.realisations.destroy', $realisation) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
