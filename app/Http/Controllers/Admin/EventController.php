<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\AvailabilityPeriod;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index(Request $request)
    {
        $query = Event::with(['admin', 'appointment']);

        // Filtre par type
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par administrateur
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('start_datetime', $request->date);
        }

        // Filtre par période
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_datetime', [
                $request->start_date,
                $request->end_date
            ]);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $events = $query->latest('start_datetime')->paginate(15)->withQueryString();

        // Récupérer les admins
        $admins = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['super_admin', 'admin']);
        })->get();

        // Statistiques
        $totalEvents = Event::count();
        $scheduledEvents = Event::where('status', 'scheduled')->count();
        $completedEvents = Event::where('status', 'completed')->count();
        $todayEvents = Event::whereDate('start_datetime', today())->count();

        return view('admin.events.index', compact(
            'events',
            'admins',
            'totalEvents',
            'scheduledEvents',
            'completedEvents',
            'todayEvents'
        ));
    }

    /**
     * Get calendar view
     */
    public function calendar(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $events = Event::with(['admin', 'appointment'])
            ->whereBetween('start_datetime', [$startDate, $endDate])
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_datetime->toIso8601String(),
                    'end' => $event->end_datetime->toIso8601String(),
                    'type' => $event->event_type,
                    'status' => $event->status,
                    'backgroundColor' => $this->getEventColor($event),
                    'url' => route('admin.events.show', $event),
                ];
            });

        return view('admin.events.calendar', compact('events', 'month', 'year'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create(Request $request)
    {
        $admins = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['super_admin', 'admin']);
        })->get();

        $availabilities = AvailabilityPeriod::active()->get();

        // Pré-remplir avec les paramètres de la requête (pour le calendrier)
        $prefilledDate = $request->get('date');
        $prefilledTime = $request->get('time');

        return view('admin.events.create', compact(
            'admins',
            'availabilities',
            'prefilledDate',
            'prefilledTime'
        ));
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'event_type' => 'required|in:daily_work,meeting,other',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required|date_format:H:i',
            'availability_period_id' => 'nullable|exists:availability_periods,id',
            'description' => 'nullable|string',
        ]);

        // Construire les datetime
        $startDatetime = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $endDatetime = Carbon::parse($validated['end_date'] . ' ' . $validated['end_time']);

        // Vérifier que end_datetime est après start_datetime
        if ($endDatetime->lte($startDatetime)) {
            return back()->withInput()
                ->with('error', 'La date/heure de fin doit être après la date/heure de début.');
        }

        // Vérifier les conflits horaires
        $hasConflict = Event::where('admin_id', $validated['admin_id'])
            ->where('status', 'scheduled')
            ->where(function($q) use ($startDatetime, $endDatetime) {
                $q->where('start_datetime', '<', $endDatetime)
                  ->where('end_datetime', '>', $startDatetime);
            })
            ->exists();

        if ($hasConflict) {
            return back()->withInput()
                ->with('error', 'Ce créneau horaire est déjà occupé.');
        }

        $event = Event::create([
            'admin_id' => $validated['admin_id'],
            'title' => $validated['title'],
            'event_type' => $validated['event_type'],
            'start_datetime' => $startDatetime,
            'end_datetime' => $endDatetime,
            'availability_period_id' => $validated['availability_period_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => 'scheduled',
        ]);

        // Logger l'activité
        activity()
            ->performedOn($event)
            ->causedBy(auth()->user())
            ->log('Création d\'événement : ' . $event->title);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Événement créé avec succès.');
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->load(['admin', 'availabilityPeriod', 'appointment']);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit(Event $event)
    {
        // Si c'est un rendez-vous, rediriger vers l'édition d'appointment
        if ($event->event_type === 'appointment' && $event->appointment) {
            return redirect()->route('admin.appointments.edit', $event->appointment)
                ->with('info', 'Pour modifier un rendez-vous, utilisez le module Rendez-vous.');
        }

        $admins = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['super_admin', 'admin']);
        })->get();

        $availabilities = AvailabilityPeriod::where('admin_id', $event->admin_id)
            ->active()
            ->get();

        return view('admin.events.edit', compact('event', 'admins', 'availabilities'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        // Si c'est un rendez-vous, ne pas permettre la modification directe
        if ($event->event_type === 'appointment' && $event->appointment) {
            return back()->with('error', 'Veuillez modifier ce rendez-vous via le module Rendez-vous.');
        }

        $validated = $request->validate([
            'admin_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'event_type' => 'required|in:daily_work,meeting,other',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required|date_format:H:i',
            'availability_period_id' => 'nullable|exists:availability_periods,id',
            'description' => 'nullable|string',
        ]);

        // Construire les datetime
        $startDatetime = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        $endDatetime = Carbon::parse($validated['end_date'] . ' ' . $validated['end_time']);

        // Vérifier que end_datetime est après start_datetime
        if ($endDatetime->lte($startDatetime)) {
            return back()->withInput()
                ->with('error', 'La date/heure de fin doit être après la date/heure de début.');
        }

        // Vérifier les conflits horaires (sauf cet événement)
        $hasConflict = Event::where('admin_id', $validated['admin_id'])
            ->where('id', '!=', $event->id)
            ->where('status', 'scheduled')
            ->where(function($q) use ($startDatetime, $endDatetime) {
                $q->where('start_datetime', '<', $endDatetime)
                  ->where('end_datetime', '>', $startDatetime);
            })
            ->exists();

        if ($hasConflict) {
            return back()->withInput()
                ->with('error', 'Ce créneau horaire est déjà occupé.');
        }

        $event->update([
            'admin_id' => $validated['admin_id'],
            'title' => $validated['title'],
            'event_type' => $validated['event_type'],
            'start_datetime' => $startDatetime,
            'end_datetime' => $endDatetime,
            'availability_period_id' => $validated['availability_period_id'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        // Logger l'activité
        activity()
            ->performedOn($event)
            ->causedBy(auth()->user())
            ->log('Modification d\'événement : ' . $event->title);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Événement mis à jour avec succès.');
    }

    /**
     * Cancel an event
     */
    public function cancel(Event $event)
    {
        $event->update(['status' => 'cancelled']);

        // Si l'événement a un rendez-vous, l'annuler aussi
        if ($event->event_type === 'appointment' && $event->appointment) {
            $event->appointment->update(['status' => 'cancelled']);
        }

        activity()
            ->performedOn($event)
            ->causedBy(auth()->user())
            ->log('Annulation d\'événement : ' . $event->title);

        return back()->with('info', 'Événement annulé.');
    }

    /**
     * Complete an event
     */
    public function complete(Event $event)
    {
        $event->update(['status' => 'completed']);

        // Si l'événement a un rendez-vous, le compléter aussi
        if ($event->event_type === 'appointment' && $event->appointment) {
            $event->appointment->update(['status' => 'completed']);
        }

        activity()
            ->performedOn($event)
            ->causedBy(auth()->user())
            ->log('Événement terminé : ' . $event->title);

        return back()->with('success', 'Événement marqué comme terminé.');
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event)
    {
        // Si c'est un rendez-vous avec appointment, ne pas permettre la suppression directe
        if ($event->event_type === 'appointment' && $event->appointment) {
            return back()->with('error', 'Veuillez supprimer ce rendez-vous via le module Rendez-vous.');
        }

        $title = $event->title;
        $event->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Suppression d\'événement : ' . $title);

        return redirect()->route('admin.events.index')
            ->with('success', 'Événement supprimé avec succès.');
    }

    /**
     * Get event color based on type and status
     */
    private function getEventColor(Event $event): string
    {
        if ($event->status === 'cancelled') {
            return '#dc3545'; // Rouge
        }

        if ($event->status === 'completed') {
            return '#6c757d'; // Gris
        }

        return match($event->event_type) {
            'appointment' => '#0d6efd', // Bleu
            'daily_work' => '#198754', // Vert
            'meeting' => '#ffc107', // Orange/Jaune
            'other' => '#6c757d', // Gris
            default => '#0dcaf0', // Cyan
        };
    }
}
