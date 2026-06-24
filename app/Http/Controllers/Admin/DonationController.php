<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\DonationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Donation::with('donor');

        // Filtre par type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par statut
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par date
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Donation::count(),
            'pending' => Donation::where('status', 'pending')->count(),
            'received' => Donation::where('status', 'received')->count(),
            'total_amount' => Donation::where('status', 'received')->sum('amount'),
        ];

        return view('admin.donations.index', compact('donations', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $donors = Donor::all();
        return view('admin.donations.create', compact('donors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_id' => 'nullable|exists:donors,id',
            'type' => 'required|in:money,cheque,object,package',
            'amount' => 'required_if:type,money,cheque|nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'items' => 'required_if:type,object,package|array',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $donation = Donation::create([
                'donor_id' => $validated['donor_id'],
                'type' => $validated['type'],
                'amount' => $validated['amount'] ?? null,
                'currency' => $validated['currency'] ?? 'XOF',
                'description' => $validated['description'],
                'status' => 'pending',
            ]);

            // Ajouter les items si applicable
            if (in_array($validated['type'], ['object', 'package']) && !empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    DonationItem::create([
                        'donation_id' => $donation->id,
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'description' => $item['description'] ?? null,
                    ]);
                }
            }

            DB::commit();

            activity()
                ->performedOn($donation)
                ->causedBy(auth()->user())
                ->log('Création d\'un don #' . $donation->id);

            return redirect()->route('admin.donations.index')
                ->with('success', 'Don enregistré avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        $donation->load(['donor', 'items', 'payments']);
        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Mark donation as received
     */
    public function receive(Donation $donation)
    {
        $donation->update([
            'status' => 'received',
            'received_at' => Carbon::now(),
        ]);

        activity()
            ->performedOn($donation)
            ->causedBy(auth()->user())
            ->log('Don #' . $donation->id . ' marqué comme reçu');

        return back()->with('success', 'Don marqué comme reçu.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $id = $donation->id;
        $donation->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Suppression du don #' . $id);

        return redirect()->route('admin.donations.index')
            ->with('success', 'Don supprimé avec succès.');
    }
}
