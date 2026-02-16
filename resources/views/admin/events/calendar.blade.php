@extends('layouts.admin')

@section('title', 'Calendrier des Événements')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .fc-event {
        cursor: pointer;
        border: none;
        padding: 2px 5px;
        border-radius: 4px;
    }

    .fc-event:hover {
        opacity: 0.8;
        transform: scale(1.02);
    }

    .fc-daygrid-event {
        white-space: normal !important;
        align-items: normal !important;
    }

    .fc-daygrid-event-dot {
        display: none;
    }

    .fc-event-time {
        font-weight: bold;
    }

    .fc-event-title {
        font-weight: normal;
    }

    .legend {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-left: 4px solid;
    }

    .stat-card.total { border-left-color: #0d6efd; }
    .stat-card.scheduled { border-left-color: #0dcaf0; }
    .stat-card.completed { border-left-color: #198754; }
    .stat-card.cancelled { border-left-color: #dc3545; }

    .stat-card h6 {
        margin: 0 0 5px 0;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .stat-card .value {
        font-size: 1.75rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: 600;
    }

    .fc-button {
        text-transform: capitalize !important;
    }

    .fc-today-button {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }

    /* Modal custom */
    .event-modal .modal-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
    }

    .event-detail-item {
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .event-detail-item:last-child {
        border-bottom: none;
    }

    .event-detail-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .event-detail-value {
        color: #2c3e50;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Calendrier des Événements</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Événements</a></li>
                    <li class="breadcrumb-item active">Calendrier</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-list"></i> Vue Liste
            </a>
            @can('events.create')
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvel Événement
            </a>
            @endcan
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-cards">
        <div class="stat-card total">
            <h6>Total Événements</h6>
            <div class="value" id="stat-total">0</div>
        </div>
        <div class="stat-card scheduled">
            <h6>Programmés</h6>
            <div class="value" id="stat-scheduled">0</div>
        </div>
        <div class="stat-card completed">
            <h6>Terminés</h6>
            <div class="value" id="stat-completed">0</div>
        </div>
        <div class="stat-card cancelled">
            <h6>Annulés</h6>
            <div class="value" id="stat-cancelled">0</div>
        </div>
    </div>

    <!-- Légende -->
    <div class="legend">
        <div class="legend-item">
            <div class="legend-color" style="background-color: #0d6efd;"></div>
            <span>Rendez-vous</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #198754;"></div>
            <span>Travail quotidien</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #ffc107;"></div>
            <span>Réunion</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #6c757d;"></div>
            <span>Autre</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #dc3545;"></div>
            <span>Annulé</span>
        </div>
    </div>

    <!-- Calendrier -->
    <div id="calendar"></div>
</div>

<!-- Modal Détails Événement -->
<div class="modal fade event-modal" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle">
                    <i class="fas fa-calendar-alt"></i> Détails de l'événement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventModalBody">
                <!-- Contenu chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="#" id="eventViewLink" class="btn btn-primary">
                    <i class="fas fa-eye"></i> Voir Détails Complets
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/fr.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste'
        },
        height: 'auto',
        navLinks: true,
        editable: false,
        dayMaxEvents: true,
        events: function(info, successCallback, failureCallback) {
            fetch(`/admin/events/calendar-data?start=${info.startStr}&end=${info.endStr}`)
                .then(response => response.json())
                .then(data => {
                    successCallback(data.events);
                    updateStats(data.stats);
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            showEventModal(info.event);
        },
        eventDidMount: function(info) {
            // Tooltip
            info.el.title = info.event.title + '\n' +
                           info.event.extendedProps.timeRange;
        }
    });

    calendar.render();

    // Fonction pour afficher le modal
    function showEventModal(event) {
        const modal = new bootstrap.Modal(document.getElementById('eventModal'));

        // Préparer les badges de type et statut
        const typeColors = {
            'appointment': 'primary',
            'daily_work': 'success',
            'meeting': 'warning',
            'other': 'secondary'
        };

        const statusColors = {
            'scheduled': 'info',
            'completed': 'success',
            'cancelled': 'danger'
        };

        const typeLabels = {
            'appointment': 'Rendez-vous',
            'daily_work': 'Travail quotidien',
            'meeting': 'Réunion',
            'other': 'Autre'
        };

        const statusLabels = {
            'scheduled': 'Programmé',
            'completed': 'Terminé',
            'cancelled': 'Annulé'
        };

        const typeBadge = `<span class="badge bg-${typeColors[event.extendedProps.type] || 'secondary'}">${typeLabels[event.extendedProps.type] || event.extendedProps.type}</span>`;
        const statusBadge = `<span class="badge bg-${statusColors[event.extendedProps.status] || 'secondary'}">${statusLabels[event.extendedProps.status] || event.extendedProps.status}</span>`;

        const content = `
            <div class="event-detail-item">
                <div class="event-detail-label">Titre</div>
                <div class="event-detail-value"><strong>${event.title}</strong></div>
            </div>
            <div class="event-detail-item">
                <div class="event-detail-label">Type</div>
                <div class="event-detail-value">${typeBadge}</div>
            </div>
            <div class="event-detail-item">
                <div class="event-detail-label">Statut</div>
                <div class="event-detail-value">${statusBadge}</div>
            </div>
            <div class="event-detail-item">
                <div class="event-detail-label">Date</div>
                <div class="event-detail-value">
                    <i class="far fa-calendar"></i> ${formatDate(event.start)}
                </div>
            </div>
            <div class="event-detail-item">
                <div class="event-detail-label">Horaires</div>
                <div class="event-detail-value">
                    <i class="far fa-clock"></i> ${event.extendedProps.timeRange}
                </div>
            </div>
            ${event.extendedProps.admin ? `
            <div class="event-detail-item">
                <div class="event-detail-label">Administrateur</div>
                <div class="event-detail-value">
                    <i class="fas fa-user-tie"></i> ${event.extendedProps.admin}
                </div>
            </div>
            ` : ''}
            ${event.extendedProps.description ? `
            <div class="event-detail-item">
                <div class="event-detail-label">Description</div>
                <div class="event-detail-value">${event.extendedProps.description}</div>
            </div>
            ` : ''}
        `;

        document.getElementById('eventModalBody').innerHTML = content;
        document.getElementById('eventViewLink').href = event.extendedProps.url;

        modal.show();
    }

    // Fonction pour mettre à jour les statistiques
    function updateStats(stats) {
        if (stats) {
            document.getElementById('stat-total').textContent = stats.total || 0;
            document.getElementById('stat-scheduled').textContent = stats.scheduled || 0;
            document.getElementById('stat-completed').textContent = stats.completed || 0;
            document.getElementById('stat-cancelled').textContent = stats.cancelled || 0;
        }
    }

    // Fonction pour formater la date
    function formatDate(date) {
        return new Date(date).toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
});
</script>
@endpush
