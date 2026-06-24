@extends('layouts.admin')

@section('title', 'Bibliographie')
@section('page-title', 'Bibliographie')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Bibliographie</li>
@endsection

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.bibliography.edit') }}" class="btn btn-success">
        <i class="fas fa-edit me-2"></i>Modifier
    </a>
</div>

<div class="custom-card">
    <div class="card-body">
        @if($bibliography)
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="bg-light rounded p-4 h-100">
                        <h4 class="mb-2">{{ $bibliography->full_name }}</h4>
                        @if($bibliography->contact)
                            <p class="mb-2"><i class="fas fa-phone text-success me-2"></i>{{ $bibliography->contact }}</p>
                        @endif
                        @if($bibliography->email)
                            <p class="mb-0"><i class="fas fa-envelope text-success me-2"></i>{{ $bibliography->email }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-lg-8">
                    <h5>Profil</h5>
                    <p class="text-muted">{{ $bibliography->profile ?: 'Non renseigne.' }}</p>

                    <h5>Parcours</h5>
                    <p class="text-muted">{{ $bibliography->parcours ?: 'Non renseigne.' }}</p>

                    <h5>Experiences</h5>
                    <p class="text-muted mb-0">{{ $bibliography->experiences ?: 'Non renseigne.' }}</p>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                <h5>Bibliographie non renseignee</h5>
                <p class="text-muted">Creez la fiche de presentation de la praticienne.</p>
                <a href="{{ route('admin.bibliography.edit') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Creer la bibliographie
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
