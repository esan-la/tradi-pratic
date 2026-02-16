@extends('layouts.admin')

@section('title', 'Gestion des Hôtels')
@section('page-title', 'Hôtels')
@section('page-description', 'Gérez vos hôtels et leurs informations')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
    <li class="breadcrumb-item active">Hôtels</li>
@endsection

@section('page-actions')
    @if(Auth::user()->hasPermission('hotels.create'))
    <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Nouvel Hôtel
    </a>
    @endif
@endsection

@section('content')
<div class="custom-card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-hotel me-2"></i>Liste des Hôtels</h5>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.hotels.index') }}" method="GET" class="row g-2">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="city" class="form-select form-select-sm">
                            <option value="">Toutes les villes</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
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
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Adresse</th>
                        <th>Contact</th>
                        <th>Chambres</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hotels as $hotel)
                    <tr>
                        <td>
                            @if($hotel->image)
                                <img src="{{ asset('storage/' . $hotel->image) }}"
                                     alt="{{ $hotel->name }}"
                                     class="rounded"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-hotel text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $hotel->name }}</strong>
                        </td>
                        <td>{{ $hotel->city }}</td>
                        <td>{{ Str::limit($hotel->address, 30) }}</td>
                        <td>
                            @if($hotel->phone)
                                <i class="fas fa-phone me-1"></i>{{ $hotel->phone }}<br>
                            @endif
                            @if($hotel->email)
                                <i class="fas fa-envelope me-1"></i>{{ $hotel->email }}
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $hotel->rooms_count }} chambres</span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.hotels.show', $hotel) }}"
                                   class="btn btn-sm btn-action btn-outline-primary"
                                   title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(Auth::user()->hasPermission('hotels.edit'))
                                <a href="{{ route('admin.hotels.edit', $hotel) }}"
                                   class="btn btn-sm btn-action btn-outline-success"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                @if(Auth::user()->hasPermission('hotels.delete'))
                                <form action="{{ route('admin.hotels.destroy', $hotel) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet hôtel ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-action btn-outline-danger"
                                            title="Supprimer">
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
                            <i class="fas fa-hotel fa-3x mb-3 d-block"></i>
                            Aucun hôtel trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($hotels->hasPages())
    <div class="card-footer">
        {{ $hotels->links() }}
    </div>
    @endif
</div>
@endsection
