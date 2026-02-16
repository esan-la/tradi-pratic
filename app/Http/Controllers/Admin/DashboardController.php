<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\HotelReservation;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        $data = [
            'totalRevenue' => 0,
            'hotelReservations' => 0,
            'pendingReservations' => 0,
            'totalOrders' => 0,
            'pendingOrders' => 0,
            'totalDonations' => 0,
            'donationsAmount' => 0,
            'recentOrders' => collect([]),
            'recentActivities' => collect([]),
            'monthlyRevenue' => array_fill(0, 12, 0),
        ];

        // Get Total Revenue
        if ($user->hasPermission('payments.view')) {
            $data['totalRevenue'] = Payment::where('status', 'completed')
                ->sum('amount');

            // Get monthly revenue for the chart
            $monthlyRevenue = Payment::where('status', 'completed')
                ->whereYear('created_at', Carbon::now()->year)
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(amount) as total')
                )
                ->groupBy('month')
                ->pluck('total', 'month');

            foreach ($monthlyRevenue as $month => $total) {
                $data['monthlyRevenue'][$month - 1] = (float) $total;
            }
        }

        // Get Hotel Reservations Stats
        if ($user->hasPermission('reservations.view')) {
            $data['hotelReservations'] = HotelReservation::count();
            $data['pendingReservations'] = HotelReservation::where('status', 'pending')->count();
        }

        // Get Orders Stats
        if ($user->hasPermission('orders.view')) {
            $data['totalOrders'] = Order::count();
            $data['pendingOrders'] = Order::where('status', 'pending')->count();

            // Get recent orders
            $data['recentOrders'] = Order::latest()
                ->limit(5)
                ->get();
        }

        // Get Donations Stats
        if ($user->hasPermission('donations.view')) {
            $data['totalDonations'] = Donation::count();
            $data['donationsAmount'] = Donation::where('status', 'received')
                ->whereNotNull('amount')
                ->sum('amount');
        }

        // Get Recent Activities
        if ($user->hasPermission('logs.view')) {
            $data['recentActivities'] = ActivityLog::latest()
                ->limit(10)
                ->get();
        }

        return view('admin.dashboard', $data);
    }

    /**
     * Get statistics for specific date range
     */
    public function getStats(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $stats = [
            'revenue' => 0,
            'orders' => 0,
            'reservations' => 0,
            'donations' => 0,
        ];

        if (auth()->user()->hasPermission('payments.view')) {
            $stats['revenue'] = Payment::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
        }

        if (auth()->user()->hasPermission('orders.view')) {
            $stats['orders'] = Order::whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }

        if (auth()->user()->hasPermission('reservations.view')) {
            $stats['reservations'] = HotelReservation::whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }

        if (auth()->user()->hasPermission('donations.view')) {
            $stats['donations'] = Donation::whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }

        return response()->json($stats);
    }

    /**
     * Get chart data
     */
    public function getChartData(Request $request)
    {
        $type = $request->input('type', 'revenue');
        $period = $request->input('period', 'monthly'); // daily, weekly, monthly, yearly

        $data = [];

        if ($type === 'revenue' && auth()->user()->hasPermission('payments.view')) {
            $data = $this->getRevenueChartData($period);
        } elseif ($type === 'orders' && auth()->user()->hasPermission('orders.view')) {
            $data = $this->getOrdersChartData($period);
        } elseif ($type === 'reservations' && auth()->user()->hasPermission('reservations.view')) {
            $data = $this->getReservationsChartData($period);
        }

        return response()->json($data);
    }

    /**
     * Get revenue chart data
     */
    private function getRevenueChartData($period)
    {
        $query = Payment::where('status', 'completed');

        switch ($period) {
            case 'daily':
                // Last 30 days
                $data = $query->whereDate('created_at', '>=', Carbon::now()->subDays(30))
                    ->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('SUM(amount) as total')
                    )
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;

            case 'weekly':
                // Last 12 weeks
                $data = $query->whereDate('created_at', '>=', Carbon::now()->subWeeks(12))
                    ->select(
                        DB::raw('YEARWEEK(created_at) as week'),
                        DB::raw('SUM(amount) as total')
                    )
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get();
                break;

            case 'yearly':
                // Last 5 years
                $data = $query->whereYear('created_at', '>=', Carbon::now()->subYears(5)->year)
                    ->select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('SUM(amount) as total')
                    )
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
                break;

            default: // monthly
                // Last 12 months
                $data = $query->whereDate('created_at', '>=', Carbon::now()->subMonths(12))
                    ->select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('SUM(amount) as total')
                    )
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();
                break;
        }

        return $data;
    }

    /**
     * Get orders chart data
     */
    private function getOrdersChartData($period)
    {
        $query = Order::query();

        switch ($period) {
            case 'daily':
                $data = $query->whereDate('created_at', '>=', Carbon::now()->subDays(30))
                    ->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;

            default: // monthly
                $data = $query->whereDate('created_at', '>=', Carbon::now()->subMonths(12))
                    ->select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();
                break;
        }

        return $data;
    }

    /**
     * Get reservations chart data
     */
    private function getReservationsChartData($period)
    {
        $query = HotelReservation::query();

        switch ($period) {
            case 'daily':
                $data = $query->whereDate('created_at', '>=', Carbon::now()->subDays(30))
                    ->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;

            default: // monthly
                $data = $query->whereDate('created_at', '>=', Carbon::now()->subMonths(12))
                    ->select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();
                break;
        }

        return $data;
    }
}
