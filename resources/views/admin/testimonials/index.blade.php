@extends('layouts.admin')

@section('title', 'Temoignages')
@section('page-title', 'Temoignages')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Temoignages</li>
@endsection

@section('content')
<div class="custom-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.testimonials.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Recherche</label>
                <input id="search" name="search" type="text" class="form-control" value="{{ request('search') }}" placeholder="Nom, lieu ou contenu">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Statut</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuves</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="rating" class="form-label">Note</label>
                <select id="rating" name="rating" class="form-select">
                    <option value="">Toutes</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ (string) request('rating') === (string) $i ? 'selected' : '' }}>{{ $i }}/5</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success flex-fill">
                    <i class="fas fa-search me-2"></i>Filtrer
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="custom-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Auteur</th>
                        <th>Temoignage</th>
                        <th>Note</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr>
                            <td>
                                <strong>{{ $testimonial->name }}</strong><br>
                                <small class="text-muted">{{ $testimonial->location ?: 'Lieu non renseigne' }}</small>
                            </td>
                            <td style="max-width: 420px;">
                                <span class="text-muted">{{ Str::limit($testimonial->content, 120) }}</span>
                            </td>
                            <td>{!! $testimonial->stars_html !!}</td>
                            <td>
                                @if($testimonial->is_approved)
                                    <span class="badge bg-success">Approuve</span>
                                @else
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @endif
                                @if($testimonial->is_featured)
                                    <span class="badge bg-info text-dark">Mis en avant</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$testimonial->is_approved)
                                    <form action="{{ route('admin.testimonials.approve', $testimonial) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce temoignage ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-comment-dots fa-2x mb-3 d-block"></i>
                                Aucun temoignage trouve.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($testimonials->hasPages())
        <div class="card-footer bg-white">
            {{ $testimonials->links() }}
        </div>
    @endif
</div>
@endsection
