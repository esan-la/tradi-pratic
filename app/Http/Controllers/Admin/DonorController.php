<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Donor::withCount('donations');

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $donors = $query->latest()->paginate(15);

        return view('admin.donors.index', compact('donors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.donors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'is_anonymous' => 'boolean',
        ]);

        $donor = Donor::create($validated);

        activity()
            ->performedOn($donor)
            ->causedBy(auth()->user())
            ->log('Création du donateur : ' . $donor->name);

        return redirect()->route('admin.donors.index')
            ->with('success', 'Donateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Donor $donor)
    {
        $donor->load(['donations' => function($query) {
            $query->latest()->with('items');
        }]);

        $stats = [
            'total_donations' => $donor->donations->count(),
            'total_amount' => $donor->donations->where('type', 'money')->sum('amount'),
        ];

        return view('admin.donors.show', compact('donor', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donor $donor)
    {
        return view('admin.donors.edit', compact('donor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donor $donor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'is_anonymous' => 'boolean',
        ]);

        $donor->update($validated);

        activity()
            ->performedOn($donor)
            ->causedBy(auth()->user())
            ->log('Modification du donateur : ' . $donor->name);

        return redirect()->route('admin.donors.index')
            ->with('success', 'Donateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donor $donor)
    {
        $name = $donor->name;
        $donor->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Suppression du donateur : ' . $name);

        return redirect()->route('admin.donors.index')
            ->with('success', 'Donateur supprimé avec succès.');
    }
}
