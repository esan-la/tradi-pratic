@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('page-title', 'Tableau de bord')
@section('page-description', 'Vue d\'ensemble de votre activité')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tableau de bord</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Total Revenue -->
    @if(Auth::user()->hasPermission('payments.view'))
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <h3>{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} FCFA</h3>
            <p>Revenus totaux</p>
            <div class="trend text-success">
                <i class="fas fa-arrow-up"></i> +12.5% ce mois
            </div>
        </div>
    </div>
    @endif

    <!-- Hotel Reservations -->
    @if(Auth::user()->hasPermission('reservations.view'))
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <i class="fas fa-bed"></i>
            </div>
            <h3>{{ $hotelReservations ?? 0 }}</h3>
            <p>Réservations d'hôtel</p>
            <div class="trend text-info">
                {{ $pendingReservations ?? 0 }} en attente
            </div>
        </div>
    </div>
    @endif

    <!-- Orders -->
    @if(Auth::user()->hasPermission('orders.view'))
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3>{{ $totalOrders ?? 0 }}</h3>
            <p>Commandes</p>
            <div class="trend text-warning">
                {{ $pendingOrders ?? 0 }} en cours
            </div>
        </div>
    </div>
    @endif

    <!-- Donations -->
    @if(Auth::user()->hasPermission('donations.view'))
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h3>{{ $totalDonations ?? 0 }}</h3>
            <p>Dons reçus</p>
            <div class="trend text-success">
                {{ number_format($donationsAmount ?? 0, 0, ',', ' ') }} FCFA
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    @if(Auth::user()->hasPermission('orders.view'))
    <div class="col-xl-8">
        <div class="custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Commandes récentes</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table custom-table mb-0">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="badge badge-status bg-warning">En attente</span>
                                    @elseif($order->status === 'paid')
                                        <span class="badge badge-status bg-info">Payée</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="badge badge-status bg-primary">Expédiée</span>
                                    @else
                                        <span class="badge badge-status bg-danger">Annulée</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-action btn-outline-primary" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Aucune commande récente</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Activity & Quick Actions -->
    <div class="col-xl-4">
        <!-- Recent Activity -->
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Activités récentes</h5>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="activity-item d-flex mb-3 pb-3 border-bottom">
                        <div class="activity-icon me-3">
                            <i class="fas fa-circle text-primary" style="font-size: 0.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1">{{ $activity->description }}</p>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-4">Aucune activité récente</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="custom-card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(Auth::user()->hasPermission('reservations.create'))
                    <a href="{{ route('admin.hotel-reservations.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>Nouvelle réservation
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('orders.create'))
                    <a href="{{ route('admin.orders.create') }}" class="btn btn-outline-success">
                        <i class="fas fa-shopping-cart me-2"></i>Nouvelle commande
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('donations.create'))
                    <a href="{{ route('admin.donations.create') }}" class="btn btn-outline-info">
                        <i class="fas fa-hand-holding-heart me-2"></i>Enregistrer un don
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Chart -->
@if(Auth::user()->hasPermission('payments.view'))
<div class="row mt-4">
    <div class="col-12">
        <div class="custom-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Évolution des revenus</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    // Revenue Chart
    @if(Auth::user()->hasPermission('payments.view'))
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        const monthlyData = {!! json_encode($monthlyRevenue ?? [0,0,0,0,0,0,0,0,0,0,0,0]) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Revenus (FCFA)',
                    data: monthlyData,
                    borderColor: '#2d6a4f',
                    backgroundColor: 'rgba(45, 106, 79, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' FCFA';
                            }
                        }
                    }
                }
            }
        });
    }
    @endif
</script>
@endpush



{{-- @extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Rendez-vous</h6>
                        <h3 class="mb-0">{{ $stats['total_appointments'] }}</h3>
                    </div>
                    <div class="text-primary" style="font-size: 2.5rem;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">En attente</h6>
                        <h3 class="mb-0">{{ $stats['pending_appointments'] }}</h3>
                    </div>
                    <div class="text-warning" style="font-size: 2.5rem;">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Réalisations</h6>
                        <h3 class="mb-0">{{ $stats['total_realisations'] }}</h3>
                    </div>
                    <div class="text-success" style="font-size: 2.5rem;">
                        <i class="fas fa-images"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Recettes</h6>
                        <h3 class="mb-0">{{ $stats['total_recipes'] }}</h3>
                    </div>
                    <div class="text-info" style="font-size: 2.5rem;">
                        <i class="fas fa-utensils"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Rendez-vous mensuels ({{ date('Y') }})</h5>
            </div>
            <div class="card-body">
                <canvas id="appointmentsChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Statut des rendez-vous</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-4">
    <!-- Recent Appointments -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Rendez-vous récents</h5>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAppointments as $appointment)
                            <tr>
                                <td>
                                    <strong>{{ $appointment->name }}</strong><br>
                                    <small class="text-muted">{{ $appointment->phone }}</small>
                                </td>
                                <td>{{ $appointment->preferred_date->format('d/m/Y') }}<br>
                                    <small class="text-muted">{{ $appointment->preferred_time }}</small>
                                </td>
                                <td>
                                    @if($appointment->status == 'pending')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($appointment->status == 'confirmed')
                                        <span class="badge bg-success">Confirmé</span>
                                    @elseif($appointment->status == 'completed')
                                        <span class="badge bg-info">Terminé</span>
                                    @else
                                        <span class="badge bg-danger">Annulé</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Aucun rendez-vous</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Rendez-vous à venir</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingAppointments as $appointment)
                            <tr>
                                <td>
                                    <strong>{{ $appointment->name }}</strong><br>
                                    <small class="text-muted">{{ $appointment->phone }}</small>
                                </td>
                                <td>{{ $appointment->preferred_date->format('d/m/Y') }}<br>
                                    <small class="text-muted">{{ $appointment->preferred_time }}</small>
                                </td>
                                <td><small>{{ $appointment->consultation_type }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Aucun rendez-vous à venir</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Appointments Chart
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(appointmentsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Rendez-vous',
                data: @json($monthlyAppointments),
                borderColor: '#2d6a4f',
                backgroundColor: 'rgba(45, 106, 79, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'Confirmé', 'Terminé'],
            datasets: [{
                data: [
                    {{ $stats['pending_appointments'] }},
                    {{ $stats['confirmed_appointments'] }},
                    {{ $stats['total_appointments'] - $stats['pending_appointments'] - $stats['confirmed_appointments'] }}
                ],
                backgroundColor: ['#ffc107', '#28a745', '#17a2b8']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush --}}
