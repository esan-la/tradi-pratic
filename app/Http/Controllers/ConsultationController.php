<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    public function index()
    {
        return view('consultations');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'consultation_type' => 'required|in:traditional,prayer,natural_care,other',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required',
            'message' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::create($validated);

        // Envoyer email de confirmation
        // Mail::to($appointment->email)->send(new AppointmentConfirmation($appointment));

        return redirect()->back()->with('success', 'Votre demande de rendez-vous a été envoyée avec succès. Nous vous contacterons bientôt.');
    }

    public function checkAvailability(Request $request)
    {
        $date = $request->input('date');

        $bookedSlots = Appointment::where('preferred_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('preferred_time')
            ->toArray();

        $allSlots = [
            '09:00', '10:00', '11:00', '12:00',
            '14:00', '15:00', '16:00', '17:00'
        ];

        $availableSlots = array_diff($allSlots, $bookedSlots);

        return response()->json([
            'available_slots' => array_values($availableSlots)
        ]);
    }
}
