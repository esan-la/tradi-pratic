@extends('layouts.app')

@section('title', $service->title)

@section('content')
<section class="service-detail py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <img src="{{ asset('storage/' . $service->image) }}"
                         class="card-img-top"
                         alt="{{ $service->title }}"
                         style="max-height: 500px; object-fit: cover;">

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h1 class="h2">{{ $service->title }}</h1>
                            @if($service->price)
                            <span class="badge bg-success fs-5 px-3 py-2">
                                {{ number_format($service->price, 0, ',', ' ') }} FCFA
                            </span>
                            @endif
                        </div>

                        <div class="service-meta mb-4 text-muted">
                            <span class="me-3">
                                <i class="fas fa-user me-1"></i>{{ $service->user->name }}
                            </span>
                            <span>
                                <i class="far fa-calendar me-1"></i>Publié le {{ $service->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        <div class="service-description">
                            {!! nl2br(e($service->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Formulaire de Contact -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-envelope me-2"></i>Contacter le prestataire</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('pub-services.contact', $service) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom complet *</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           required
                                           value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           required
                                           value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="tel"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Votre message *</label>
                                <textarea name="message"
                                          rows="5"
                                          class="form-control @error('message') is-invalid @enderror"
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Info Prestataire -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Proposé par</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $service->user->name }}</h6>
                                <small class="text-muted">Prestataire</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Similaires -->
                @if($relatedServices->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Autres services</h5>
                    </div>
                    <div class="card-body p-3">
                        @foreach($relatedServices as $related)
                        <a href="{{ route('pub-services.show', $related->slug) }}" class="text-decoration-none">
                            <div class="d-flex mb-3 pb-3 border-bottom">
                                <img src="{{ asset('storage/' . $related->image) }}"
                                     alt="{{ $related->title }}"
                                     class="rounded me-3"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1">{{ Str::limit($related->title, 40) }}</h6>
                                    @if($related->price)
                                    <span class="text-success fw-bold">
                                        {{ number_format($related->price, 0, ',', ' ') }} FCFA
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
