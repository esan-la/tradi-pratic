@extends('layouts.admin')

@section('title', 'Message de ' . $contact->name)
@section('page-title', 'Message de Contact')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Messages</a></li>
<li class="breadcrumb-item active">Message #{{ $contact->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Message Content -->
        <div class="custom-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-envelope me-2"></i>{{ $contact->subject }}
                </h5>
                @if($contact->status === 'new')
                    <span class="badge bg-danger">Nouveau</span>
                @elseif($contact->status === 'read')
                    <span class="badge bg-info">Lu</span>
                @else
                    <span class="badge bg-success">Répondu</span>
                @endif
            </div>
            <div class="card-body">
                <!-- Sender Info -->
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center"
                         style="width: 50px; height: 50px;">
                        <span class="text-white fw-bold fs-4">{{ substr($contact->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $contact->name }}</h6>
                        <small class="text-muted">
                            @if($contact->email)
                                <i class="fas fa-envelope me-1"></i>{{ $contact->email }}
                            @endif
                            @if($contact->phone)
                                <span class="ms-3"><i class="fas fa-phone me-1"></i>{{ $contact->phone }}</span>
                            @endif
                        </small>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">
                            {{ $contact->created_at->format('d/m/Y à H:i') }}<br>
                            <i class="far fa-clock me-1"></i>{{ $contact->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>

                <!-- Message Body -->
                <div class="message-content">
                    <p class="lead mb-3">{{ $contact->subject }}</p>
                    <div style="white-space: pre-line;">{{ $contact->message }}</div>
                </div>
            </div>
        </div>

        <!-- Reply Form -->
        @if(Auth::user()->hasPermission('contacts.reply'))
        <div class="custom-card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-reply me-2"></i>Répondre au Message</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                    @csrf

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        La réponse sera envoyée à <strong>{{ $contact->email }}</strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Votre réponse *</label>
                        <textarea name="reply_message"
                                  rows="6"
                                  class="form-control @error('reply_message') is-invalid @enderror"
                                  placeholder="Écrivez votre réponse ici..."
                                  required>{{ old('reply_message') }}</textarea>
                        @error('reply_message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer la Réponse
                        </button>
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Admin Notes -->
        @if($contact->admin_notes)
        <div class="custom-card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Notes Administrateur</h5>
            </div>
            <div class="card-body">
                <div style="white-space: pre-line;" class="text-muted">{{ $contact->admin_notes }}</div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Actions Rapides</h5>
            </div>
            <div class="card-body">
                @if($contact->status !== 'replied')
                <div class="d-grid gap-2 mb-3">
                    <a href="#replyForm" class="btn btn-success">
                        <i class="fas fa-reply me-2"></i>Répondre
                    </a>
                </div>
                @endif

                @if(Auth::user()->hasPermission('contacts.delete'))
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.contacts.destroy', $contact) }}"
                          method="POST"
                          onsubmit="return confirm('Supprimer ce message ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Contact Info -->
        <div class="custom-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations Contact</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Nom complet</label>
                    <p class="mb-0"><strong>{{ $contact->name }}</strong></p>
                </div>

                @if($contact->email)
                <div class="mb-3">
                    <label class="text-muted small">Email</label>
                    <p class="mb-0">
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                    </p>
                </div>
                @endif

                @if($contact->phone)
                <div class="mb-3">
                    <label class="text-muted small">Téléphone</label>
                    <p class="mb-0">
                        <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                    </p>
                </div>
                @endif

                <hr>

                <div class="mb-3">
                    <label class="text-muted small">Reçu le</label>
                    <p class="mb-0"><strong>{{ $contact->created_at->format('d/m/Y à H:i') }}</strong></p>
                </div>

                <div class="mb-0">
                    <label class="text-muted small">Statut</label>
                    <p class="mb-0">
                        @if($contact->status === 'new')
                            <span class="badge bg-danger">Nouveau</span>
                        @elseif($contact->status === 'read')
                            <span class="badge bg-info">Lu</span>
                        @else
                            <span class="badge bg-success">Répondu</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Add Note -->
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Ajouter une Note</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.contacts.add-note', $contact) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="note"
                                  rows="3"
                                  class="form-control form-control-sm"
                                  placeholder="Ajouter une note interne..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Enregistrer la Note
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
