@extends('layouts.admin')

@section('title', 'Gestion des réalisations')
@section('page-title', 'Réalisations')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Liste des réalisations</h5>
        <a href="{{ route('admin.realisations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle réalisation
        </a>
    </div>

    <!-- Filters -->
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('admin.realisations.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="published" class="form-select">
                    <option value="">Tous</option>
                    <option value="1" {{ request('published') === '1' ? 'selected' : '' }}>Publiées</option>
                    <option value="0" {{ request('published') === '0' ? 'selected' : '' }}>Brouillons</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.realisations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Grid View -->
    <div class="card-body">
        <div class="row g-4">
            @forelse($realisations as $realisation)
            <div class="col-md-4">
                <div class="card h-100 border shadow-sm">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $realisation->image) }}" class="card-img-top" alt="{{ $realisation->title }}" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 p-2">
                            @if($realisation->is_featured)
                                <span class="badge bg-warning"><i class="fas fa-star"></i> Vedette</span>
                            @endif
                            @if($realisation->is_published)
                                <span class="badge bg-success">Publiée</span>
                            @else
                                <span class="badge bg-secondary">Brouillon</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">{{ $realisation->category }}</span>
                        <h5 class="card-title">{{ $realisation->title }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($realisation->description, 100) }}</p>
                        <p class="text-muted small mb-0">
                            <i class="far fa-calendar"></i> {{ $realisation->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.realisations.show', $realisation) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            <a href="{{ route('admin.realisations.edit', $realisation) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="{{ route('admin.realisations.destroy', $realisation) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réalisation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    <i class="fas fa-images fa-3x mb-3"></i>
                    <p>Aucune réalisation trouvée</p>
                    <a href="{{ route('admin.realisations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer la première réalisation
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($realisations->hasPages())
    <div class="card-footer bg-white">
        {{ $realisations->links() }}
    </div>
    @endif
</div>
@endsection
