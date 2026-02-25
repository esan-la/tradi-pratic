@extends('layouts.admin')

@section('title', $recipe->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            {{-- Image principale --}}
            @if($recipe->image)
                <img src="{{ asset('storage/' . $recipe->image) }}" class="img-fluid rounded mb-4">
            @endif

            {{-- Titre --}}
            <h1>{{ $recipe->title }}</h1>

            {{-- Meta info --}}
            <div class="d-flex gap-3 mb-4">
                <span><i class="fas fa-clock"></i> {{ ($recipe->prep_time ?? 0) + ($recipe->cook_time ?? 0) }} min</span>
                <span><i class="fas fa-users"></i> {{ $recipe->servings }} personnes</span>
                <span class="badge bg-{{ $recipe->difficulty == 'Facile' ? 'success' : ($recipe->difficulty == 'Moyen' ? 'warning' : 'danger') }}">
                    {{ $recipe->difficulty }}
                </span>
            </div>

            {{-- Description --}}
            <div class="card mb-4">
                <div class="card-body">
                    {!! $recipe->description !!}
                </div>
            </div>

            {{-- Ingrédients --}}
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5><i class="fas fa-shopping-basket"></i> Ingrédients</h5>
                </div>
                <div class="card-body">
                    <ul>
                        @foreach($recipe->ingredients as $ingredient)
                            <li>{{ $ingredient }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-list-ol"></i> Instructions</h5>
                </div>
                <div class="card-body">
                    <ol>
                        @foreach($recipe->instructions as $instruction)
                            <li class="mb-3">{{ $instruction }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>

            {{-- Galerie --}}
            @if($recipe->gallery && count($recipe->gallery) > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-images"></i> Galerie</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($recipe->gallery as $image)
                                <div class="col-md-4">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Vidéo --}}
            {{-- @if($recipe->video_url)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $recipe->video_url }}"></iframe>
                        </div>
                    </div>
                </div>
            @endif --}}
            <!-- Vidéo -->
                @if($recipe->video_url)
                    <div class="mb-5">
                        <h4 class="mb-4">
                            @if(str_contains($recipe->video_url, 'youtube') || str_contains($recipe->video_url, 'youtu.be'))
                                <i class="fab fa-youtube text-danger me-2"></i>
                            @elseif(str_contains($recipe->video_url, 'vimeo'))
                                <i class="fab fa-vimeo text-info me-2"></i>
                            @else
                                <i class="fas fa-video text-primary me-2"></i>
                            @endif
                            Vidéo de la recette
                        </h4>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="ratio ratio-16x9">
                                    @php
                                        $videoUrl = $recipe->video_url;
                                        if (str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be')) {
                                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $videoUrl, $match);
                                            $embedUrl = isset($match[1]) ? "https://www.youtube.com/embed/{$match[1]}" : $videoUrl;
                                        }
                                        elseif (str_contains($videoUrl, 'vimeo.com')) {
                                            preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $match);
                                            $embedUrl = isset($match[1]) ? "https://player.vimeo.com/video/{$match[1]}" : $videoUrl;
                                        }
                                        else {
                                            $embedUrl = $videoUrl;
                                        }
                                    @endphp
                                    <iframe src="{{ $embedUrl }}"
                                            allowfullscreen
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            loading="lazy">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
        </div>

        <div class="col-lg-4">
            {{-- Statistiques --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6>Statistiques</h6>
                </div>
                <div class="card-body">
                    <p><strong>Vues:</strong> {{ $recipe->views ?? 0 }}</p>
                    <p><strong>Créé:</strong> {{ $recipe->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Modifié:</strong> {{ $recipe->updated_at->diffForHumans() }}</p>
                    <p><strong>Statut:</strong>
                        <span class="badge bg-{{ $recipe->is_published ? 'success' : 'secondary' }}">
                            {{ $recipe->is_published ? 'Publié' : 'Brouillon' }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.recipes.edit', $recipe) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('recipes.show', $recipe->slug) }}" target="_blank" class="btn btn-info">
                        <i class="fas fa-eye"></i> Voir sur le site
                    </a>
                    <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
