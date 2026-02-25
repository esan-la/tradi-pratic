@extends('layouts.admin')

@section('title', 'Gestion des Recettes')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Gestion des Recettes</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Recettes</li>
                </ol>
            </nav>
        </div>
        <div>
            @if(Auth::user()->hasPermission('recipes.create'))
                <a href="{{ route('admin.recipes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvelle recette
                </a>
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-utensils fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Publiées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['published'] }}</div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En vedette</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['featured'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Brouillons</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['draft'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filtres
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.recipes.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <!-- Recherche -->
                    <div class="col-md-3">
                        <label class="form-label small">Recherche</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Titre, description..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Catégorie -->
                    <div class="col-md-2">
                        <label class="form-label small">Catégorie</label>
                        <select name="category" class="form-select">
                            <option value="">Toutes</option>
                            @foreach($categories as $key => $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Difficulté -->
                    <div class="col-md-2">
                        <label class="form-label small">Difficulté</label>
                        <select name="difficulty" class="form-select">
                            <option value="">Toutes</option>
                            @foreach($difficulties as $key => $difficulty)
                                <option value="{{ $difficulty }}" {{ request('difficulty') == $difficulty ? 'selected' : '' }}>
                                    {{ $difficulty }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Statut -->
                    <div class="col-md-2">
                        <label class="form-label small">Statut</label>
                        <select name="published" class="form-select">
                            <option value="">Tous</option>
                            <option value="1" {{ request('published') === '1' ? 'selected' : '' }}>Publiées</option>
                            <option value="0" {{ request('published') === '0' ? 'selected' : '' }}>Brouillons</option>
                        </select>
                    </div>

                    <!-- Tri -->
                    <div class="col-md-2">
                        <label class="form-label small">Trier par</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Plus récent</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus ancien</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Titre (A-Z)</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Plus vues</option>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="col-md-1 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request()->hasAny(['search', 'category', 'difficulty', 'published', 'sort']))
                                <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Actions en masse -->
    @if(Auth::user()->hasPermission('recipes.delete'))
        <div class="mb-3" id="bulkActions" style="display: none;">
            <button type="button" class="btn btn-danger btn-sm" onclick="bulkDelete()">
                <i class="fas fa-trash me-2"></i>Supprimer la sélection (<span id="selectedCount">0</span>)
            </button>
        </div>
    @endif

    <!-- Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Liste des recettes ({{ $recipes->total() }})
            </h6>
        </div>
        <div class="card-body">
            @if($recipes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                @if(Auth::user()->hasPermission('recipes.delete'))
                                    <th width="30">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                @endif
                                <th width="80">Image</th>
                                <th>Titre</th>
                                <th width="120">Catégorie</th>
                                <th width="100">Difficulté</th>
                                <th width="100">Temps</th>
                                <th width="80">Vues</th>
                                <th width="80">Statut</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recipes as $recipe)
                                <tr>
                                    @if(Auth::user()->hasPermission('recipes.delete'))
                                        <td>
                                            <input type="checkbox" class="recipe-checkbox" value="{{ $recipe->id }}">
                                        </td>
                                    @endif
                                    <td>
                                        @if($recipe->image)
                                            <img src="{{ asset('storage/' . $recipe->image) }}"
                                                 class="img-thumbnail"
                                                 style="width: 60px; height: 60px; object-fit: cover;"
                                                 alt="{{ $recipe->title }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $recipe->title }}</strong>
                                            @if($recipe->is_featured)
                                                <span class="badge bg-warning text-dark ms-1">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            @endif
                                        </div>
                                        @if($recipe->short_description)
                                            <small class="text-muted d-block">
                                                {{ Str::limit($recipe->short_description, 50) }}
                                            </small>
                                        @endif
                                        <small class="text-muted">
                                            <i class="fas fa-utensils me-1"></i>{{ count($recipe->ingredients) }} ingrédients
                                            · <i class="fas fa-list-ol me-1"></i>{{ count($recipe->instructions) }} étapes
                                        </small>
                                    </td>
                                    <td>
                                        @if($recipe->category)
                                            <span class="badge bg-info">{{ $recipe->category }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($recipe->difficulty)
                                            <span class="badge bg-{{ $recipe->difficulty == 'Facile' ? 'success' : ($recipe->difficulty == 'Moyen' ? 'warning' : 'danger') }}">
                                                {{ $recipe->difficulty }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $totalTime = ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0);
                                        @endphp
                                        @if($totalTime > 0)
                                            <small>
                                                <i class="far fa-clock"></i> {{ $totalTime }} min
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-eye"></i> {{ $recipe->views ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(Auth::user()->hasPermission('recipes.edit'))
                                            <form action="{{ route('admin.recipes.toggle', $recipe) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm {{ $recipe->is_published ? 'btn-success' : 'btn-secondary' }}">
                                                    <i class="fas fa-{{ $recipe->is_published ? 'eye' : 'eye-slash' }}"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-{{ $recipe->is_published ? 'success' : 'secondary' }}">
                                                {{ $recipe->is_published ? 'Publié' : 'Brouillon' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if(Auth::user()->hasPermission('recipes.view'))
                                                <a href="{{ route('admin.recipes.show', $recipe) }}"
                                                   class="btn btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if(Auth::user()->hasPermission('recipes.edit'))
                                                <a href="{{ route('admin.recipes.edit', $recipe) }}"
                                                   class="btn btn-primary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.recipes.toggleFeatured', $recipe) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn {{ $recipe->is_featured ? 'btn-warning' : 'btn-outline-warning' }}"
                                                            title="Vedette">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(Auth::user()->hasPermission('recipes.delete'))
                                                <button type="button"
                                                        class="btn btn-danger"
                                                        onclick="confirmDelete({{ $recipe->id }})"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $recipes->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune recette trouvée</p>
                    @if(Auth::user()->hasPermission('recipes.create'))
                        <a href="{{ route('admin.recipes.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-2"></i>Créer la première recette
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Form pour suppression -->
@if(Auth::user()->hasPermission('recipes.delete'))
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endif

@endsection

@push('styles')
<style>
.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
.border-left-info { border-left: 4px solid #36b9cc !important; }
</style>
@endpush

@push('scripts')
<script>
// Select all
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.recipe-checkbox').forEach(cb => cb.checked = this.checked);
    updateBulkActions();
});

document.querySelectorAll('.recipe-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const checked = document.querySelectorAll('.recipe-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const count = document.getElementById('selectedCount');

    if (bulkActions && count) {
        bulkActions.style.display = checked.length > 0 ? 'block' : 'none';
        count.textContent = checked.length;
    }
}

function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette recette ?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/recipes/${id}`;
        form.submit();
    }
}

function bulkDelete() {
    const checked = Array.from(document.querySelectorAll('.recipe-checkbox:checked'))
        .map(cb => cb.value);

    if (checked.length === 0) return;

    if (confirm(`Supprimer ${checked.length} recette(s) ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.recipes.bulkDelete") }}';

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
}
</script>
@endpush
