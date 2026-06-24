<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::with('hotel');

        // Filtre par hôtel
        if ($request->has('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        // Filtre par type
        if ($request->has('room_type')) {
            $query->where('room_type', $request->room_type);
        }

        // Filtre par disponibilité
        if ($request->has('is_available')) {
            $query->where('is_available', $request->is_available);
        }

        $rooms = $query->paginate(15)->withQueryString();
        $hotels = Hotel::all();

        return view('admin.rooms.index', compact('rooms', 'hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hotels = Hotel::all();
        $roomTypes = ['single', 'double', 'suite', 'family'];

        return view('admin.rooms.create', compact('hotels', 'roomTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_number' => 'required|string|max:50',
            'room_type' => 'required|in:single,double,suite,family',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'boolean',
        ]);

        $room = Room::create($validated);

        return redirect()->route('admin.hotels.show', $room->hotel_id)
            ->with('success', 'Chambre ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load('hotel', 'reservations');
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $hotels = Hotel::all();
        $roomTypes = ['single', 'double', 'suite', 'family'];

        return view('admin.rooms.edit', compact('room', 'hotels', 'roomTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_number' => 'required|string|max:50',
            'room_type' => 'required|in:single,double,suite,family',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'boolean',
        ]);

        $room->update($validated);

        return redirect()->route('admin.hotels.show', $room->hotel_id)
            ->with('success', 'Chambre mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $hotelId = $room->hotel_id;
        $room->delete();

        return redirect()->route('admin.hotels.show', $hotelId)
            ->with('success', 'Chambre supprimée avec succès.');
    }

    /**
     * Toggle availability
     */
    public function toggleAvailability(Room $room)
    {
        $room->update(['is_available' => !$room->is_available]);

        return back()->with('success', 'Disponibilité mise à jour avec succès.');
    }
}
