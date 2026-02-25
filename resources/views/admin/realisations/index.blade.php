@extends('layouts.admin')

@section('title', 'Gestion des Réalisations')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Gestion des Réalisations</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Réalisations</li>
                </ol>
            </nav>
        </div>
        <div>
            @if(Auth::user()->hasPermission('realisations.create'))
                <a href="{{ route('admin.realisations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvelle réalisation
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
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
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
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
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
            <form action="{{ route('admin.realisations.index') }}" method="GET" id="filterForm">
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

                    <!-- Statut publication -->
                    <div class="col-md-2">
                        <label class="form-label small">Statut</label>
                        <select name="published" class="form-select">
                            <option value="">Tous</option>
                            <option value="1" {{ request('published') === '1' ? 'selected' : '' }}>Publiées</option>
                            <option value="0" {{ request('published') === '0' ? 'selected' : '' }}>Brouillons</option>
                        </select>
                    </div>

                    <!-- Vedette -->
                    <div class="col-md-2">
                        <label class="form-label small">Vedette</label>
                        <select name="featured" class="form-select">
                            <option value="">Toutes</option>
                            <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Non</option>
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
                            @if(request()->hasAny(['search', 'category', 'published', 'featured', 'sort']))
                                <a href="{{ route('admin.realisations.index') }}" class="btn btn-secondary">
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
    @if(Auth::user()->hasPermission('realisations.delete'))
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
                Liste des réalisations ({{ $realisations->total() }})
            </h6>
        </div>
        <div class="card-body">
            @if($realisations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                @if(Auth::user()->hasPermission('realisations.delete'))
                                    <th width="30">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                @endif
                                <th width="80">Image</th>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <thclass="text-center">Vues</th>
                                <th >Date</th>
                                <th>Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($realisations as $realisation)
                                <tr>
                                    @if(Auth::user()->hasPermission('realisations.delete'))
                                        <td>
                                            <input type="checkbox" class="realisation-checkbox" value="{{ $realisation->id }}">
                                        </td>
                                    @endif
                                    <td>
                                        @if($realisation->image)
                                            <img src="{{ asset('storage/' . $realisation->image) }}"
                                                 class="img-thumbnail"
                                                 style="width: 60px; height: 60px; object-fit: cover;"
                                                 alt="{{ $realisation->title }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $realisation->title }}</strong>
                                            @if($realisation->is_featured)
                                                <span class="badge bg-warning text-dark ms-1">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            @endif
                                        </div>
                                        @if($realisation->short_description)
                                            <small class="text-muted d-block">
                                                {{ Str::limit($realisation->short_description, 60) }}
                                            </small>
                                        @endif
                                        <small class="text-muted">
                                            <i class="fas fa-link"></i>
                                            <code>{{ $realisation->slug }}</code>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $realisation->category }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-eye"></i> {{ $realisation->views ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $realisation->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        @if(Auth::user()->hasPermission('realisations.edit'))
                                            <form action="{{ route('admin.realisations.toggle', $realisation) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm {{ $realisation->is_published ? 'btn-success' : 'btn-secondary' }}"
                                                        title="{{ $realisation->is_published ? 'Publié' : 'Brouillon' }}">
                                                    <i class="fas fa-{{ $realisation->is_published ? 'eye' : 'eye-slash' }}"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-{{ $realisation->is_published ? 'success' : 'secondary' }}">
                                                {{ $realisation->is_published ? 'Publié' : 'Brouillon' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if(Auth::user()->hasPermission('realisations.view'))
                                                <a href="{{ route('admin.realisations.show', $realisation) }}"
                                                   class="btn btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if(Auth::user()->hasPermission('realisations.edit'))
                                                <a href="{{ route('admin.realisations.edit', $realisation) }}"
                                                   class="btn btn-primary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.realisations.toggleFeatured', $realisation) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn {{ $realisation->is_featured ? 'btn-warning' : 'btn-outline-warning' }}"
                                                            title="Vedette">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(Auth::user()->hasPermission('realisations.delete'))
                                                <button type="button"
                                                        class="btn btn-danger"
                                                        onclick="confirmDelete({{ $realisation->id }})"
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
                    {{ $realisations->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune réalisation trouvée</p>
                    @can('realisations.create')
                        <a href="{{ route('admin.realisations.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-2"></i>Créer la première réalisation
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Form pour suppression -->
@if(Auth::user()->hasPermission('realisations.delete'))
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
// Select all checkboxes
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.realisation-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBulkActions();
});

// Update bulk actions visibility
document.querySelectorAll('.realisation-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const checked = document.querySelectorAll('.realisation-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const count = document.getElementById('selectedCount');

    if (bulkActions && count) {
        bulkActions.style.display = checked.length > 0 ? 'block' : 'none';
        count.textContent = checked.length;
    }
}

// Confirm delete
function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette réalisation ?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/realisations/${id}`;
        form.submit();
    }
}

// Bulk delete
function bulkDelete() {
    const checked = Array.from(document.querySelectorAll('.realisation-checkbox:checked'))
        .map(cb => cb.value);

    if (checked.length === 0) return;

    if (confirm(`Supprimer ${checked.length} réalisation(s) ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.realisations.bulkDelete") }}';

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
