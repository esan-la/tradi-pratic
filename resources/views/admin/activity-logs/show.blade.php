@extends('layouts.admin')

@section('title', 'Detail activite')
@section('page-title', 'Detail activite')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.activity-logs.index') }}">Activites</a></li>
<li class="breadcrumb-item active">#{{ $activityLog->id }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock-rotate-left me-2"></i>{{ $activityLog->description }}</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Date</dt>
                    <dd class="col-sm-9">{{ $activityLog->created_at?->format('d/m/Y H:i:s') }}</dd>

                    <dt class="col-sm-3">Utilisateur</dt>
                    <dd class="col-sm-9">{{ $activityLog->user_name }}</dd>

                    <dt class="col-sm-3">Log</dt>
                    <dd class="col-sm-9">{{ $activityLog->log_name ?? 'default' }}</dd>

                    <dt class="col-sm-3">Evenement</dt>
                    <dd class="col-sm-9">{{ $activityLog->event ?? '-' }}</dd>

                    <dt class="col-sm-3">Sujet</dt>
                    <dd class="col-sm-9">
                        {{ $activityLog->subject_type ? class_basename($activityLog->subject_type) : '-' }}
                        @if($activityLog->subject_id)
                            <small class="text-muted">({{ $activityLog->subject_id }})</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">IP</dt>
                    <dd class="col-sm-9">{{ $activityLog->ip_address ?? '-' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-code me-2"></i>Proprietes</h5>
            </div>
            <div class="card-body">
                <pre class="bg-light rounded p-3 mb-0 small" style="white-space: pre-wrap;">{{ json_encode($activityLog->properties?->toArray() ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>
@endsection
