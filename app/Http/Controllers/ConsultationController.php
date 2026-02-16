<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Event;
use App\Models\AvailabilityPeriod;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConsultationController extends Controller
{
    /**
     * Afficher la page de consultations
     */
    public function index()
    {
        return view('consultations');
    }

    /**
     * Vérifier les créneaux disponibles pour une date donnée
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        // Récupérer le premier admin disponible (ou celui par défaut)
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->first();

        if (!$admin) {
            return response()->json([
                'available_slots' => [],
                'message' => 'Aucun administrateur disponible'
            ]);
        }

        // Récupérer les disponibilités pour ce jour
        $availabilities = AvailabilityPeriod::active()
            ->where('admin_id', $admin->id)
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
                    $isBooked = Event::where('admin_id', $admin->id)
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
            'available_slots' => $slots,
        ]);
    }

    /**
     * Enregistrer une demande de rendez-vous
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'provenance' => 'required|string|max:255',
            'consultation_type' => 'required|in:traditional,prayer,natural_care,Consultation_spirituelle,Autres',
            'autre_consultation' => 'nullable|required_if:consultation_type,Autres|string|max:255',
            'message' => 'nullable|string',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
        ]);

        try {
            \DB::beginTransaction();

            // Récupérer le premier admin
            $admin = User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })->first();

            if (!$admin) {
                return back()->withInput()->with('error', 'Aucun administrateur disponible. Veuillez nous contacter directement.');
            }

            // Parser le créneau horaire
            $timeSlot = explode('-', $validated['preferred_time']);
            if (count($timeSlot) !== 2) {
                return back()->withInput()->with('error', 'Créneau horaire invalide.');
            }

            $startDatetime = Carbon::parse($validated['preferred_date'] . ' ' . trim($timeSlot[0]));
            $endDatetime = Carbon::parse($validated['preferred_date'] . ' ' . trim($timeSlot[1]));

            // Vérifier la disponibilité
            $hasConflict = Event::where('admin_id', $admin->id)
                ->scheduled()
                ->where(function($q) use ($startDatetime, $endDatetime) {
                    $q->where('start_datetime', '<', $endDatetime)
                      ->where('end_datetime', '>', $startDatetime);
                })
                ->exists();

            if ($hasConflict) {
                return back()->withInput()->with('error', 'Ce créneau horaire n\'est plus disponible. Veuillez en choisir un autre.');
            }

            // 1. Créer l'événement
            $event = Event::create([
                'admin_id' => $admin->id,
                'title' => 'Consultation - ' . $validated['name'],
                'event_type' => 'appointment',
                'start_datetime' => $startDatetime,
                'end_datetime' => $endDatetime,
                'description' => $validated['message'],
                'status' => 'scheduled',
            ]);

            // 2. Créer le rendez-vous
            $appointment = Appointment::create([
                'event_id' => $event->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'provenance' => $validated['provenance'],
                'consultation_type' => $validated['consultation_type'],
                'autre_consultation' => $validated['autre_consultation'] ?? null,
                'message' => $validated['message'],
                'status' => 'pending', // En attente de confirmation
            ]);

            \DB::commit();

            // TODO: Envoyer une notification par SMS/Email à l'admin
            // TODO: Envoyer une confirmation au client

            return redirect()->route('consultations')
                ->with('success', 'Votre demande de rendez-vous a été envoyée avec succès ! Nous vous contacterons dans les 24h pour confirmation.');

        } catch (\Exception $e) {
            \DB::rollBack();

            \Log::error('Erreur lors de la création du rendez-vous: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer ou nous contacter directement.');
        }
    }
}
