<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        // Filtre par utilisateur
        if ($request->has('user_name')) {
            $query->where('user_name', $request->user_name);
        }

        // Filtre par action
        if ($request->has('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        // Filtre par type de sujet
        if ($request->has('subject_type')) {
            $query->where('subject_type', $request->subject_type);
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
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(20);

        // Récupérer les utilisateurs uniques pour le filtre
        $users = ActivityLog::distinct('user_name')
            ->pluck('user_name')
            ->filter()
            ->sort();

        // Types de sujets uniques
        $subjectTypes = ActivityLog::distinct('subject_type')
            ->pluck('subject_type')
            ->filter()
            ->sort();

        return view('admin.activity-logs.index', compact('logs', 'users', 'subjectTypes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityLog $activityLog)
    {
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Clear old logs
     */
    public function clear(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $date = now()->subDays($validated['days']);
        $count = ActivityLog::where('created_at', '<', $date)->count();

        ActivityLog::where('created_at', '<', $date)->delete();

        activity()
            ->causedBy(auth()->user())
            ->log("Suppression de $count logs de plus de {$validated['days']} jours");

        return back()->with('success', "$count enregistrement(s) supprimé(s).");
    }

    /**
     * Export logs
     */
    public function export(Request $request)
    {
        $query = ActivityLog::query();

        // Appliquer les mêmes filtres que l'index
        if ($request->has('user_name')) {
            $query->where('user_name', $request->user_name);
        }
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->get();

        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, ['Date', 'Utilisateur', 'Action', 'Description', 'IP', 'User Agent']);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user_name ?? 'Système',
                    $log->action ?? '-',
                    $log->description ?? '-',
                    $log->ip_address ?? '-',
                    $log->user_agent ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
