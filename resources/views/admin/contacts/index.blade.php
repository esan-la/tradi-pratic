@extends('layouts.admin')

@section('title', 'Messages de Contact')
@section('page-title', 'Gestion des Messages')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
<li class="breadcrumb-item active">Messages</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Messages</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-envelope-open"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['new'] }}</h3>
                <p>Nouveaux</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['read'] }}</h3>
                <p>Lus</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-reply"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $stats['replied'] }}</h3>
                <p>Répondus</p>
            </div>
        </div>
    </div>
</div>

<div class="custom-card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-inbox me-2"></i>Boîte de Réception</h5>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.contacts.index') }}" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Tous statuts</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Nouveau</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lu</option>
                            <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Répondu</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>Expéditeur</th>
                        <th>Sujet</th>
                        <th>Message</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr class="{{ $contact->status === 'new' ? 'table-primary' : '' }}">
                        <td>
                            <input type="checkbox" class="form-check-input contact-checkbox"
                                   value="{{ $contact->id }}">
                        </td>
                        <td>
                            <strong class="{{ $contact->status === 'new' ? 'fw-bold' : '' }}">
                                {{ $contact->name }}
                            </strong><br>
                            <small class="text-muted">
                                @if($contact->email)
                                    <i class="fas fa-envelope me-1"></i>{{ $contact->email }}<br>
                                @endif
                                @if($contact->phone)
                                    <i class="fas fa-phone me-1"></i>{{ $contact->phone }}
                                @endif
                            </small>
                        </td>
                        <td>
                            <strong class="{{ $contact->status === 'new' ? 'fw-bold' : '' }}">
                                {{ Str::limit($contact->subject, 40) }}
                            </strong>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($contact->message, 60) }}</small>
                        </td>
                        <td>
                            @if($contact->status === 'new')
                                <span class="badge bg-danger">
                                    <i class="fas fa-circle fa-xs me-1"></i>Nouveau
                                </span>
                            @elseif($contact->status === 'read')
                                <span class="badge bg-info">Lu</span>
                            @else
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Répondu
                                </span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $contact->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.contacts.show', $contact) }}"
                                   class="btn btn-sm btn-action btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(Auth::user()->hasPermission('contacts.delete'))
                                <form action="{{ route('admin.contacts.destroy', $contact) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce message ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action btn-outline-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            Aucun message trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($contacts->hasPages())
    <div class="card-footer">
        {{ $contacts->links() }}
    </div>
    @endif
</div>

<!-- Actions Groupées -->
<div class="custom-card mt-4" id="bulkActions" style="display: none;">
    <div class="card-body">
        <form id="bulkActionForm" method="POST">
            @csrf
            <div class="row align-items-center">
                <div class="col-md-3">
                    <span id="selectedCount">0</span> message(s) sélectionné(s)
                </div>
                <div class="col-md-6">
                    <select name="action" class="form-select" required>
                        <option value="">Choisir une action...</option>
                        <option value="mark_read">Marquer comme lu</option>
                        <option value="mark_replied">Marquer comme répondu</option>
                        <option value="delete">Supprimer</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check me-2"></i>Appliquer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const bulkForm = document.getElementById('bulkActionForm');

    // Select all
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    // Individual checkboxes
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const selected = document.querySelectorAll('.contact-checkbox:checked');
        selectedCount.textContent = selected.length;
        bulkActions.style.display = selected.length > 0 ? 'block' : 'none';
    }

    // Bulk action form submit
    bulkForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const selected = Array.from(document.querySelectorAll('.contact-checkbox:checked'))
            .map(cb => cb.value);

        if (selected.length === 0) {
            alert('Veuillez sélectionner au moins un message');
            return;
        }

        const action = this.querySelector('[name="action"]').value;
        if (!action) {
            alert('Veuillez choisir une action');
            return;
        }

        if (action === 'delete' && !confirm('Supprimer les messages sélectionnés ?')) {
            return;
        }

        // Submit form
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('action', action);
        selected.forEach(id => formData.append('contacts[]', id));

        fetch('{{ route("admin.contacts.bulk-action") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload();
        })
        .catch(error => {
            alert('Erreur lors de l\'action groupée');
        });
    });
});
</script>
@endpush
