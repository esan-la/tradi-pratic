<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Models\HotelReservation;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::query();

        // Filtre par méthode de paiement
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
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

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payer_name', 'like', "%{$search}%")
                  ->orWhere('payer_email', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Payment::count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'total_amount' => Payment::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['orders', 'hotelReservations', 'donations']);

        // Déterminer le type de paiement
        $paymentType = null;
        $relatedItem = null;

        if ($payment->orders->count() > 0) {
            $paymentType = 'order';
            $relatedItem = $payment->orders->first();
        } elseif ($payment->hotelReservations->count() > 0) {
            $paymentType = 'reservation';
            $relatedItem = $payment->hotelReservations->first();
        } elseif ($payment->donations->count() > 0) {
            $paymentType = 'donation';
            $relatedItem = $payment->donations->first();
        }

        return view('admin.payments.show', compact('payment', 'paymentType', 'relatedItem'));
    }

    /**
     * Process payment for an order
     */
    public function processOrderPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $payment = Payment::create([
            'payer_name' => $order->customer_name,
            'payer_email' => $order->customer_email,
            'payer_phone' => $order->customer_phone,
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'],
            'currency' => 'XOF',
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Lier au paiement
        $payment->orders()->attach($order->id);

        // Mettre à jour la commande
        $order->update(['status' => 'paid']);

        activity()
            ->performedOn($payment)
            ->causedBy(auth()->user())
            ->log('Paiement de ' . number_format($validated['amount']) . ' FCFA pour la commande #' . $order->id);

        return back()->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Process payment for a hotel reservation
     */
    public function processHotelPayment(Request $request, HotelReservation $reservation)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $payment = Payment::create([
            'payer_name' => $reservation->guest_name,
            'payer_email' => $reservation->guest_email,
            'payer_phone' => $reservation->guest_phone,
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'],
            'currency' => 'XOF',
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Lier au paiement
        $payment->hotelReservations()->attach($reservation->id);

        // Mettre à jour la réservation
        $reservation->update(['payment_status' => 'paid']);

        activity()
            ->performedOn($payment)
            ->causedBy(auth()->user())
            ->log('Paiement de ' . number_format($validated['amount']) . ' FCFA pour la réservation #' . $reservation->id);

        return back()->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Process donation payment
     */
    public function processDonationPayment(Request $request)
    {
        $validated = $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'payment_method' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $donation = Donation::with('donor')->findOrFail($validated['donation_id']);

        $payment = Payment::create([
            'payer_name' => $donation->donor->name ?? 'Anonyme',
            'payer_email' => $donation->donor->email,
            'payer_phone' => $donation->donor->phone,
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'],
            'currency' => 'XOF',
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Lier au paiement
        $payment->donations()->attach($donation->id);

        activity()
            ->performedOn($payment)
            ->causedBy(auth()->user())
            ->log('Paiement de don de ' . number_format($validated['amount']) . ' FCFA');

        return back()->with('success', 'Paiement de don enregistré avec succès.');
    }
}
