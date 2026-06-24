@extends('layouts.admin')

@section('title', 'Modifier la bibliographie')
@section('page-title', 'Modifier la bibliographie')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.bibliography.index') }}">Bibliographie</a></li>
<li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="custom-card">
    <div class="card-body">
        <form action="{{ route('admin.bibliography.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="full_name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                    <input id="full_name" name="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $bibliography->full_name) }}" required>
                    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input id="contact" name="contact" type="text" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact', $bibliography->contact) }}">
                    @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $bibliography->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="profile" class="form-label">Profil</label>
                <textarea id="profile" name="profile" rows="5" class="form-control @error('profile') is-invalid @enderror">{{ old('profile', $bibliography->profile) }}</textarea>
                @error('profile')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="parcours" class="form-label">Parcours</label>
                <textarea id="parcours" name="parcours" rows="5" class="form-control @error('parcours') is-invalid @enderror">{{ old('parcours', $bibliography->parcours) }}</textarea>
                @error('parcours')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label for="experiences" class="form-label">Experiences</label>
                <textarea id="experiences" name="experiences" rows="5" class="form-control @error('experiences') is-invalid @enderror">{{ old('experiences', $bibliography->experiences) }}</textarea>
                @error('experiences')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.bibliography.index') }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
