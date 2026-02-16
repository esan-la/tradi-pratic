<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Donor;
use App\Models\DonationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    /**
     * Show public donation form
     */
    public function publicForm()
    {
        return view('dons.donate');
    }

    /**
     * Store a public donation
     */
    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'donor_phone' => 'required|string|max:50',
            'donor_address' => 'nullable|string|max:255',
            'is_anonymous' => 'boolean',

            'donation_type' => 'required|in:money,cheque,object,package',
            'amount' => 'required_if:donation_type,money,cheque|nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'description' => 'nullable|string',

            // Pour les dons en nature
            'items' => 'required_if:donation_type,object,package|array',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Créer ou récupérer le donateur
            $donor = Donor::firstOrCreate(
                [
                    'email' => $validated['donor_email'],
                    'phone' => $validated['donor_phone'],
                ],
                [
                    'name' => $validated['donor_name'],
                    'address' => $validated['donor_address'] ?? null,
                    'is_anonymous' => $validated['is_anonymous'] ?? false,
                ]
            );

            // Créer le don
            $donation = Donation::create([
                'donor_id' => $donor->id,
                'type' => $validated['donation_type'],
                'amount' => $validated['amount'] ?? null,
                'currency' => $validated['currency'] ?? 'XOF',
                'description' => $validated['description'] ?? null,
                'status' => 'pending',
            ]);

            // Ajouter les items si don en nature
            if (in_array($validated['donation_type'], ['object', 'package']) && !empty($validated['items'])) {
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

            return redirect()->route('home')
                ->with('success', 'Merci pour votre générosité ! Votre don a été enregistré et sera traité prochainement.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre don. Veuillez réessayer.');
        }
    }
}
