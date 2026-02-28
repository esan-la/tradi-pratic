<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EmailService;
use App\Models\Appointment;
use App\Models\Event;
use App\Models\User;
use App\Models\Payment;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    protected $mediaService;
    protected EmailService $emailService;

    public function __construct(MediaStorageService $mediaService, EmailService $emailService)
    {
        $this->mediaService = $mediaService;
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of appointments
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['event', 'event.admin']);

        // Filtre par statut
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filtre par type de consultation
        if ($request->has('consultation_type') && $request->consultation_type != '') {
            $query->where('consultation_type', $request->consultation_type);
        }

        // Filtre par date
        if ($request->has('date') && $request->date != '') {
            $date = $request->date;
            $query->whereHas('event', function($q) use ($date) {
                $q->whereDate('start_datetime', $date);
            });
        }

        // Recherche
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $appointments = $query->latest()->paginate(15);

        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();

        return view('admin.appointments.create', compact('admins'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Event fields
            'admin_id' => 'required|exists:users,id',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',

            // Appointment fields
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:255',
            'provenance' => 'required|string|max:255',

            // Document
            'doctype' => 'nullable|string|max:255',
            'docnumber' => 'nullable|string|max:255',
            'imagedoc' => 'nullable|image|max:5120', // 5MB

            // Consultation
            'consultation_type' => 'required|in:traditional,prayer,natural_care,Consultation_spirituelle,Autres',
            'autre_consultation' => 'nullable|required_if:consultation_type,Autres|string|max:255',
            'message' => 'nullable|string',

            // Payment
            'amount' => 'nullable|numeric|min:0',
        ]);

        try {
            \DB::beginTransaction();

            // Vérifier les conflits horaires
            $hasConflict = Event::where('admin_id', $validated['admin_id'])
                ->scheduled()
                ->where(function($q) use ($validated) {
                    $q->where('start_datetime', '<', $validated['end_datetime'])
                      ->where('end_datetime', '>', $validated['start_datetime']);
                })
                ->exists();

            if ($hasConflict) {
                return back()->withInput()->with('error', 'Ce créneau horaire est déjà occupé.');
            }

            // 1. Créer l'événement
            $event = Event::create([
                'admin_id' => $validated['admin_id'],
                'title' => 'Consultation - ' . $validated['name'],
                'event_type' => 'appointment',
                'start_datetime' => $validated['start_datetime'],
                'end_datetime' => $validated['end_datetime'],
                'status' => 'scheduled',
            ]);

            // Upload du document si fourni
            if ($request->hasFile('imagedoc')) {
                $validated['imagedoc'] = $this->mediaService->uploadImage(
                    $request->file('imagedoc'),
                    'appointments/documents'
                );
            }

            // 2. Créer le rendez-vous
            $appointment = Appointment::create([
                'event_id' => $event->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'provenance' => $validated['provenance'],
                'doctype' => $validated['doctype'],
                'docnumber' => $validated['docnumber'],
                'imagedoc' => $validated['imagedoc'] ?? null,
                'consultation_type' => $validated['consultation_type'],
                'autre_consultation' => $validated['autre_consultation'] ?? null,
                'message' => $validated['message'],
                'status' => 'pending',
            ]);

            // 3. Créer le paiement si montant fourni
            if (!empty($validated['amount']) && $validated['amount'] > 0) {
                Payment::create([
                    'appointment_id' => $appointment->id,
                    'amount' => $validated['amount'],
                    'status' => 'pending', // Adapter selon votre système
                    'payment_method' => null,
                ]);
            }

            // Logger l'activité
            try {
                if (function_exists('activity')) {
                    activity()
                        ->performedOn($appointment)
                        ->causedBy(auth()->user())
                        ->log('Création du rendez-vous : ' . $appointment->name);
                }
            } catch (\Exception $e) {
                // Ignorer si activity log n'est pas disponible
            }

            // Envoi email en queue
            $this->emailService->sendAppointmentConfirmation($appointment);

            \DB::commit();

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Rendez-vous créé avec succès.');

        } catch (\Exception $e) {
            \DB::rollBack();

            // Supprimer l'image uploadée en cas d'erreur
            if (isset($validated['imagedoc'])) {
                $this->mediaService->delete($validated['imagedoc']);
            }

            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified appointment
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['event', 'event.admin', 'payments']);
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit(Appointment $appointment)
    {
        $appointment->load(['event']);

        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();

        return view('admin.appointments.edit', compact('appointment', 'admins'));
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            // Event fields
            'admin_id' => 'required|exists:users,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',

            // Appointment fields
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:255',
            'provenance' => 'required|string|max:255',

            // Document
            'doctype' => 'nullable|string|max:255',
            'docnumber' => 'nullable|string|max:255',
            'imagedoc' => 'nullable|image|max:5120', // 5MB

            // Consultation
            'consultation_type' => 'required|in:traditional,prayer,natural_care,Consultation_spirituelle,Autres',
            'autre_consultation' => 'nullable|required_if:consultation_type,Autres|string|max:255',
            'message' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        try {
            \DB::beginTransaction();

            // Vérifier les conflits horaires (sauf cet événement)
            $hasConflict = Event::where('admin_id', $validated['admin_id'])
                ->where('id', '!=', $appointment->event_id)
                ->scheduled()
                ->where(function($q) use ($validated) {
                    $q->where('start_datetime', '<', $validated['end_datetime'])
                      ->where('end_datetime', '>', $validated['start_datetime']);
                })
                ->exists();

            if ($hasConflict) {
                return back()->withInput()->with('error', 'Ce créneau horaire est déjà occupé.');
            }

            // 1. Mettre à jour l'événement
            $appointment->event->update([
                'admin_id' => $validated['admin_id'],
                'title' => 'Consultation - ' . $validated['name'],
                'start_datetime' => $validated['start_datetime'],
                'end_datetime' => $validated['end_datetime'],
            ]);

            // Upload du nouveau document si fourni
            if ($request->hasFile('imagedoc')) {
                // Supprimer l'ancien document
                if ($appointment->imagedoc) {
                    $this->mediaService->delete($appointment->imagedoc);
                }

                $validated['imagedoc'] = $this->mediaService->uploadImage(
                    $request->file('imagedoc'),
                    'appointments/documents'
                );
            }

            // 2. Mettre à jour le rendez-vous
            $appointment->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'provenance' => $validated['provenance'],
                'doctype' => $validated['doctype'],
                'docnumber' => $validated['docnumber'],
                'imagedoc' => $validated['imagedoc'] ?? $appointment->imagedoc,
                'consultation_type' => $validated['consultation_type'],
                'autre_consultation' => $validated['autre_consultation'] ?? null,
                'message' => $validated['message'],
                'admin_notes' => $validated['admin_notes'],
            ]);

            // Logger l'activité
            try {
                if (function_exists('activity')) {
                    activity()
                        ->performedOn($appointment)
                        ->causedBy(auth()->user())
                        ->log('Modification du rendez-vous : ' . $appointment->name);
                }
            } catch (\Exception $e) {
                // Ignorer
            }

            \DB::commit();

            return redirect()->route('admin.appointments.show', $appointment)
                ->with('success', 'Rendez-vous mis à jour avec succès.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $appointment->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $appointment->admin_notes,
        ]);

        // Mettre à jour le statut de l'événement en conséquence
        $eventStatus = match($validated['status']) {
            'cancelled' => 'cancelled',
            'completed' => 'completed',
            default => 'scheduled',
        };

        $appointment->event->update(['status' => $eventStatus]);

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($appointment)
                    ->causedBy(auth()->user())
                    ->log("Changement de statut du rendez-vous : {$validated['status']}");
            }
        } catch (\Exception $e) {
            // Ignorer
        }
        // Envoi email en queue
        $this->emailService->sendAppointmentStatusChanged($appointment, $appointment->getOriginal('status'));

        return back()->with('success', 'Statut mis à jour avec succès.');
    }

    /**
     * Confirm an appointment
     */
    public function confirm(Appointment $appointment)
    {
        $appointment->update(['status' => 'confirmed']);
        $appointment->event->update(['status' => 'scheduled']);

        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($appointment)
                    ->causedBy(auth()->user())
                    ->log('Confirmation du rendez-vous : ' . $appointment->name);
            }
        } catch (\Exception $e) {
            // Ignorer
        }

        // TODO: Envoyer notification au client
        // Mail::to($appointment->email)->send(new AppointmentConfirmed($appointment));

        return back()->with('success', 'Rendez-vous confirmé avec succès.');
    }

    /**
     * Cancel an appointment
     */
    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);
        $appointment->event->update(['status' => 'cancelled']);

        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($appointment)
                    ->causedBy(auth()->user())
                    ->log('Annulation du rendez-vous : ' . $appointment->name);
            }
        } catch (\Exception $e) {
            // Ignorer
        }

        // TODO: Envoyer notification au client
        // Mail::to($appointment->email)->send(new AppointmentCancelled($appointment));

        return back()->with('info', 'Rendez-vous annulé.');
    }

    /**
     * Complete an appointment
     */
    public function complete(Appointment $appointment)
    {
        $appointment->update(['status' => 'completed']);
        $appointment->event->update(['status' => 'completed']);

        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($appointment)
                    ->causedBy(auth()->user())
                    ->log('Rendez-vous terminé : ' . $appointment->name);
            }
        } catch (\Exception $e) {
            // Ignorer
        }

        return back()->with('success', 'Rendez-vous marqué comme terminé.');
    }

    /**
     * Remove the specified appointment
     */
    public function destroy(Appointment $appointment)
    {
        $name = $appointment->name;

        try {
            \DB::beginTransaction();

            // Supprimer le document
            if ($appointment->imagedoc) {
                $this->mediaService->delete($appointment->imagedoc);
            }

            // Supprimer l'événement (cascade sur appointment)
            $appointment->event->delete();

            try {
                if (function_exists('activity')) {
                    activity()
                        ->causedBy(auth()->user())
                        ->log('Suppression du rendez-vous : ' . $name);
                }
            } catch (\Exception $e) {
                // Ignorer
            }

            \DB::commit();

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Rendez-vous supprimé avec succès.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableSlots(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $adminId = $validated['admin_id'];
        $date = Carbon::parse($validated['date']);
        $dayOfWeek = $date->dayOfWeek;

        // Récupérer les disponibilités pour ce jour
        $availabilities = \App\Models\AvailabilityPeriod::active()
            ->where('admin_id', $adminId)
            ->where('day_of_week', $dayOfWeek)
            ->get()
            ->filter(fn($period) => $period->isValidForDate($date));

        $slots = [];

        foreach ($availabilities as $period) {
            $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $period->start_time);
            $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $period->end_time);

            // Générer des créneaux de 30 minutes
            while ($startTime->lt($endTime)) {
                $slotEnd = $startTime->copy()->addMinutes(30);

                if ($slotEnd->lte($endTime)) {
                    // Vérifier si le créneau est libre
                    $isBooked = Event::where('admin_id', $adminId)
                        ->scheduled()
                        ->where(function($q) use ($startTime, $slotEnd) {
                            $q->where('start_datetime', '<', $slotEnd)
                              ->where('end_datetime', '>', $startTime);
                        })
                        ->exists();

                    if (!$isBooked) {
                        $slots[] = [
                            'start' => $startTime->format('H:i'),
                            'end' => $slotEnd->format('H:i'),
                            'available' => true,
                        ];
                    }
                }

                $startTime->addMinutes(30);
            }
        }

        return response()->json([
            'date' => $date->format('Y-m-d'),
            'slots' => $slots,
        ]);
    }
}
