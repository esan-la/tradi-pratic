<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Realisation;
use App\Models\Appointment;
use App\Models\Contact;
use App\Models\Recipe;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = [
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::pending()->count(),
            'confirmed_appointments' => Appointment::confirmed()->count(),
            'total_revenue' => Payment::completed()->sum('amount'),
            'new_contacts' => Contact::new()->count(),
            'total_recipes' => Recipe::published()->count(),
            'total_realisations' => Realisation::count(),
        ];

        $upcomingAppointments = Appointment::upcoming()
            ->orderBy('preferred_date')
            ->orderBy('preferred_time')
            ->limit(10)
            ->get();

        $recentAppointments  = Appointment::latest()->limit(5)->get();
        $recentContacts = Contact::latest()->limit(5)->get();

        // Statistiques mensuelles
        $monthlyRevenue = Payment::completed()
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyAppointments = Appointment::whereYear('created_at', Carbon::now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats',
            'upcomingAppointments',
            'recentContacts',
            'monthlyRevenue',
            'monthlyAppointments',
            'recentAppointments'
        ));
    }
}
