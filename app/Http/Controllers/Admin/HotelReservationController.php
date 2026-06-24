<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelReservation;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HotelReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HotelReservation::with(['hotel', 'room']);

        // Filtre par hôtel
        if ($request->has('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        // Filtre par statut
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par statut de paiement
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filtre par date
        if ($request->has('date_from')) {
            $query->where('check_in', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('check_out', '<=', $request->date_to);
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%")
                  ->orWhere('guest_phone', 'like', "%{$search}%");
            });
        }

        $reservations = $query->latest()->paginate(15)->withQueryString();
        $hotels = Hotel::all();

        return view('admin.hotels.reservations.index', compact('reservations', 'hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hotels = Hotel::all();
        return view('admin.hotels.reservations.create', compact('hotels'));
    }

    /**
     * Get available rooms for a hotel
     */
    public function getAvailableRooms(Request $request)
    {
        $rooms = Room::where('hotel_id', $request->hotel_id)
            ->where('is_available', true)
            ->get();

        return response()->json($rooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Calculer le nombre de nuits
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $totalNights = $checkOut->diffInDays($checkIn);

        // Récupérer le prix de la chambre
        $room = Room::findOrFail($validated['room_id']);
        $totalAmount = $room->price_per_night * $totalNights;

        $reservation = HotelReservation::create([
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'],
            'hotel_id' => $validated['hotel_id'],
            'room_id' => $validated['room_id'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'total_nights' => $totalNights,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        activity()
            ->performedOn($reservation)
            ->causedBy(auth()->user())
            ->log('Création d\'une réservation pour ' . $reservation->guest_name);

        return redirect()->route('admin.hotel-reservations.index')
            ->with('success', 'Réservation créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelReservation $hotelReservation)
    {
        $hotelReservation->load(['hotel', 'room', 'payments']);
        return view('admin.hotels.reservations.show', compact('hotelReservation'));
    }

    /**
     * Confirm a reservation
     */
    public function confirm(HotelReservation $hotelReservation)
    {
        $hotelReservation->update(['status' => 'confirmed']);

        activity()
            ->performedOn($hotelReservation)
            ->causedBy(auth()->user())
            ->log('Confirmation de la réservation #' . $hotelReservation->id);

        return back()->with('success', 'Réservation confirmée avec succès.');
    }

    /**
     * Cancel a reservation
     */
    public function cancel(HotelReservation $hotelReservation)
    {
        $hotelReservation->update(['status' => 'cancelled']);

        activity()
            ->performedOn($hotelReservation)
            ->causedBy(auth()->user())
            ->log('Annulation de la réservation #' . $hotelReservation->id);

        return back()->with('warning', 'Réservation annulée.');
    }

    /**
     * Complete a reservation
     */
    public function complete(HotelReservation $hotelReservation)
    {
        $hotelReservation->update(['status' => 'completed']);

        activity()
            ->performedOn($hotelReservation)
            ->causedBy(auth()->user())
            ->log('Clôture de la réservation #' . $hotelReservation->id);

        return back()->with('success', 'Réservation clôturée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelReservation $hotelReservation)
    {
        $id = $hotelReservation->id;
        $hotelReservation->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Suppression de la réservation #' . $id);

        return redirect()->route('admin.hotel-reservations.index')
            ->with('success', 'Réservation supprimée avec succès.');
    }
}
