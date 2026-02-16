@extends('layouts.app')

@section('title', 'Services & Actualités')

@section('content')
<section class="page-header bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 mb-3">Services & Actualités</h1>
                <p class="lead text-muted">Découvrez tous nos services disponibles</p>
            </div>
            <div class="col-md-4">
                <form action="{{ route('pub-services.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Rechercher..."
                               value="{{ request('search') }}">
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="all-services py-5">
    <div class="container">
        @if($services->count() > 0)
        <div class="row g-4">
            @foreach($services as $service)
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('pub-services.show', $service->slug) }}" class="text-decoration-none">
                    <div class="service-card h-100">
                        <div class="service-image-wrapper">
                            <img src="{{ asset('storage/' . $service->image) }}"
                                 alt="{{ $service->title }}"
                                 class="img-fluid">
                            @if($service->price)
                            <span class="price-badge">{{ number_format($service->price, 0, ',', ' ') }} FCFA</span>
                            @endif
                        </div>
                        <div class="service-content p-3">
                            <h5 class="service-title">{{ $service->title }}</h5>
                            <p class="service-description text-muted small">
                                {{ Str::limit($service->description, 80) }}
                            </p>
                            <div class="service-footer">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>{{ $service->user->name }}
                                </small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $services->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
            <h3>Aucun service trouvé</h3>
        </div>
        @endif
    </div>
</section>
@endsection
