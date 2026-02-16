@extends('layouts.admin')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Utilisateurs</li>
@endsection

@section('page-actions')
@if(Auth::user()->hasPermission('users.create'))
<a href="{{ route('admin.users.create') }}" class="btn btn-primary-custom">
    <i class="fas fa-user-plus me-2"></i>Nouvel Utilisateur
</a>
@endif
@endsection

@section('content')
<div class="custom-card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Liste des Utilisateurs</h5>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <select name="role" class="form-select form-select-sm">
                            <option value="">Tous les rôles</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
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
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Rôles</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                         class="rounded-circle me-2"
                                         width="40" height="40"
                                         alt="{{ $user->name }}">
                                @else
                                    <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-info">{{ ucfirst($role->name) }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($user->is_active ?? true)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="btn btn-sm btn-action btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(Auth::user()->hasPermission('users.edit'))
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-sm btn-action btn-outline-success" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                @if(Auth::user()->hasPermission('users.delete') && $user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cet utilisateur ?');">
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
                            <i class="fas fa-users fa-3x mb-3 d-block"></i>
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
    <div class="card-footer">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
