@extends('layouts.admin')

@section('title', 'Temoignage')
@section('page-title', 'Detail du temoignage')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.testimonials.index') }}">Temoignages</a></li>
<li class="breadcrumb-item active">{{ $testimonial->name }}</li>
@endsection

@section('content')
<div class="custom-card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h4 class="mb-1">{{ $testimonial->name }}</h4>
                <p class="text-muted mb-2">
                    <i class="fas fa-location-dot me-1"></i>{{ $testimonial->location ?: 'Lieu non renseigne' }}
                </p>
                <div>{!! $testimonial->stars_html !!}</div>
            </div>
            <div>
                @if($testimonial->is_approved)
                    <span class="badge bg-success">Approuve</span>
                @else
                    <span class="badge bg-warning text-dark">En attente</span>
                @endif
            </div>
        </div>

        <div class="bg-light rounded p-4 mb-4">
            <p class="mb-0 fs-5">{{ $testimonial->content }}</p>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <div class="d-flex gap-2">
                @if(!$testimonial->is_approved)
                    <form action="{{ route('admin.testimonials.approve', $testimonial) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Approuver
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-ban me-2"></i>Rejeter
                        </button>
                    </form>
                @endif
                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Supprimer ce temoignage ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
