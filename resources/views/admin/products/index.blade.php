@extends('layouts.admin')

@section('title', 'Produits')
@section('page-title', 'Gestion des Produits')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Produits</li>
@endsection

@section('page-actions')
@if(Auth::user()->hasPermission('products.create'))
<a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom">
    <i class="fas fa-plus me-2"></i>Nouveau Produit
</a>
@endif
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-details">
                <h3>{{ \App\Models\Product::count() }}</h3>
                <p>Total Produits</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3>{{ \App\Models\Product::where('is_published', true)->count() }}</h3>
                <p>Publiés</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-details">
                <h3>{{ \App\Models\Product::where('stock', '<=', 10)->count() }}</h3>
                <p>Stock Faible</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-details">
                <h3>{{ \App\Models\Product::where('stock', 0)->count() }}</h3>
                <p>Rupture de Stock</p>
            </div>
        </div>
    </div>
</div>

<div class="custom-card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Produits</h5>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2">
                    <div class="col-md-3">
                        <select name="is_published" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Publiés</option>
                            <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Non publiés</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="stock_filter" class="form-select form-select-sm">
                            <option value="">Tout stock</option>
                            <option value="low_stock" {{ request('stock_filter') == 'low_stock' ? 'selected' : '' }}>Stock faible</option>
                            <option value="out_of_stock" {{ request('stock_filter') == 'out_of_stock' ? 'selected' : '' }}>Rupture</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="rounded"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong><br>
                            <small class="text-muted">{{ $product->slug }}</small>
                        </td>
                        <td>
                            <strong>{{ number_format($product->price, 0, ',', ' ') }} FCFA</strong>
                            @if($product->compare_price)
                                <br><small class="text-muted text-decoration-line-through">
                                    {{ number_format($product->compare_price, 0, ',', ' ') }} FCFA
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($product->stock == 0)
                                <span class="badge bg-danger">Rupture</span>
                            @elseif($product->stock <= 10)
                                <span class="badge bg-warning">{{ $product->stock }} restant(s)</span>
                            @else
                                <span class="badge bg-success">{{ $product->stock }} en stock</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_published)
                                <span class="badge bg-success">Publié</span>
                            @else
                                <span class="badge bg-secondary">Brouillon</span>
                            @endif
                        </td>
                        <td>{{ $product->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.products.show', $product) }}"
                                   class="btn btn-sm btn-action btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(Auth::user()->hasPermission('products.edit'))
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="btn btn-sm btn-action btn-outline-success" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                @if(Auth::user()->hasPermission('products.delete'))
                                <form action="{{ route('admin.products.destroy', $product) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce produit ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action btn-outline-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                            Aucun produit trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
    <div class="card-footer">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
