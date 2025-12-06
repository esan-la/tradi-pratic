@extends('layouts.admin')

@section('title', 'Détails de la recette')
@section('page-title', $recipe->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            @if($recipe->image)
            <img src="{{ asset('storage/' . $recipe->image) }}" class="card-img-top" alt="{{ $recipe->title }}" style="max-height: 400px; object-fit: cover;">
            @endif
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h3>{{ $recipe->title }}</h3>
                        <p class="text-muted">{{ $recipe->description }}</p>
                    </div>
                    @if($recipe->is_published)
                        <span class="badge bg-success">Publiée</span>
                    @else
                        <span class="badge bg-secondary">Brouillon</span>
                    @endif
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                            <p class="mb-0"><strong>Préparation</strong></p>
                            <p class="mb-0">{{ $recipe->prep_time ?? 0 }} min</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-fire fa-2x text-danger mb-2"></i>
                            <p class="mb-0"><strong>Cuisson</strong></p>
                            <p class="mb-0">{{ $recipe->cook_time ?? 0 }} min</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-users fa-2x text-success mb-2"></i>
                            <p class="mb-0"><strong>Portions</strong></p>
                            <p class="mb-0">{{ $recipe->servings ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3">Ingrédients</h5>
                <ul class="list-group mb-4">
                    @foreach($recipe->ingredients as $ingredient)
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success me-2"></i>{{ $ingredient }}
                        </li>
                    @endforeach
                </ul>

                <h5 class="mb-3">Instructions</h5>
                <ol class="list-group list-group-numbered">
                    @foreach($recipe->instructions as $instruction)
                        <li class="list-group-item">{{ $instruction }}</li>
                    @endforeach
                </ol>

                @if($recipe->video_url)
                <div class="mt-4">
                    <h5 class="mb-3">Vidéo</h5>
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ str_replace('watch?v=', 'embed/', $recipe->video_url) }}" allowfullscreen></iframe>
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
                <p><strong>Créée le:</strong><br>{{ $recipe->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Modifiée le:</strong><br>{{ $recipe->updated_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Slug:</strong><br><small class="text-muted">{{ $recipe->slug }}</small></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.recipes.edit', $recipe) }}" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
                <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-info w-100 mb-2" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>Voir sur le site
                </a>
                <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary w-100 mb-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <form action="{{ route('admin.recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
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
