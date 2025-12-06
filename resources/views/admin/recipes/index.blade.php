@extends('layouts.admin')

@section('title', 'Gestion des recettes')
@section('page-title', 'Recettes')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Liste des recettes</h5>
        <a href="{{ route('admin.recipes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle recette
        </a>
    </div>

    <!-- Filters -->
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('admin.recipes.index') }}" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Rechercher une recette..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="published" class="form-select">
                    <option value="">Toutes les recettes</option>
                    <option value="1" {{ request('published') === '1' ? 'selected' : '' }}>Publiées</option>
                    <option value="0" {{ request('published') === '0' ? 'selected' : '' }}>Non publiées</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Titre</th>
                        <th>Temps</th>
                        <th>Portions</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recipes as $recipe)
                    <tr>
                        <td>
                            @if($recipe->image)
                                <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-utensils text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $recipe->title }}</strong><br>
                            <small class="text-muted">{{ Str::limit($recipe->description, 50) }}</small>
                        </td>
                        <td>
                            <small>
                                <i class="far fa-clock"></i> {{ ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0) }} min
                            </small>
                        </td>
                        <td>
                            <small><i class="fas fa-users"></i> {{ $recipe->servings ?? 'N/A' }}</small>
                        </td>
                        <td>
                            @if($recipe->is_published)
                                <span class="badge bg-success">Publiée</span>
                            @else
                                <span class="badge bg-secondary">Brouillon</span>
                            @endif
                        </td>
                        <td><small>{{ $recipe->created_at->format('d/m/Y') }}</small></td>
                        <td class="table-actions">
                            <a href="{{ route('admin.recipes.show', $recipe) }}" class="btn btn-sm btn-info" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.recipes.edit', $recipe) }}" class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.recipes.destroy', $recipe) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Aucune recette trouvée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($recipes->hasPages())
    <div class="card-footer bg-white">
        {{ $recipes->links() }}
    </div>
    @endif
</div>
@endsection
