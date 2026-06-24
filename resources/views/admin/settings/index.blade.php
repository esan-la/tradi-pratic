@extends('layouts.admin')

@section('title', 'Parametres')
@section('page-title', 'Parametres')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Parametres</li>
@endsection

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="d-flex justify-content-end mb-3">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-2"></i>Enregistrer
        </button>
    </div>

    @forelse($settings as $group => $items)
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-sliders-h me-2"></i>{{ ucfirst(str_replace('_', ' ', $group ?: 'general')) }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($items as $setting)
                        <div class="col-lg-6 mb-3">
                            <label for="setting_{{ $setting->key }}" class="form-label fw-semibold">
                                {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                            </label>

                            @if(in_array($setting->type, ['textarea', 'text_area']))
                                <textarea id="setting_{{ $setting->key }}"
                                          name="settings[{{ $setting->key }}]"
                                          class="form-control"
                                          rows="4">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                            @elseif(in_array($setting->type, ['boolean', 'bool']))
                                <select id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]" class="form-select">
                                    <option value="1" {{ (string) old('settings.' . $setting->key, $setting->value) === '1' ? 'selected' : '' }}>Oui</option>
                                    <option value="0" {{ (string) old('settings.' . $setting->key, $setting->value) === '0' ? 'selected' : '' }}>Non</option>
                                </select>
                            @else
                                <input id="setting_{{ $setting->key }}"
                                       name="settings[{{ $setting->key }}]"
                                       type="{{ $setting->type === 'email' ? 'email' : 'text' }}"
                                       class="form-control"
                                       value="{{ old('settings.' . $setting->key, $setting->value) }}">
                            @endif

                            <small class="text-muted">{{ $setting->key }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <div class="custom-card">
            <div class="card-body text-center py-5">
                <i class="fas fa-sliders-h fa-3x text-muted mb-3"></i>
                <h5>Aucun parametre configure</h5>
                <p class="text-muted mb-0">Ajoutez des parametres via les seeders ou l'API interne.</p>
            </div>
        </div>
    @endforelse
</form>
@endsection
