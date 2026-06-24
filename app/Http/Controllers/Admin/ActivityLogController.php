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
        $query = ActivityLog::with(['causer', 'subject']);

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->filled('action')) {
            $query->where(function ($q) use ($request) {
                $q->where('event', 'like', "%{$request->action}%")
                    ->orWhere('log_name', 'like', "%{$request->action}%");
            });
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('log_name', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhereHas('causer', function ($causerQuery) use ($search) {
                        $causerQuery->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        $users = User::whereIn('id', ActivityLog::query()
            ->where('causer_type', User::class)
            ->whereNotNull('causer_id')
            ->select('causer_id'))
            ->orderBy('prenom')
            ->get();

        $subjectTypes = ActivityLog::query()
            ->whereNotNull('subject_type')
            ->distinct()
            ->pluck('subject_type')
            ->sort()
            ->values();

        return view('admin.activity-logs.index', compact('logs', 'users', 'subjectTypes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load(['causer', 'subject']);

        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Clear old logs.
     */
    public function clear(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $date = now()->subDays($validated['days']);
        $count = ActivityLog::where('created_at', '<', $date)->count();

        ActivityLog::where('created_at', '<', $date)->delete();

        activity('system')
            ->causedBy(auth()->user())
            ->log("Suppression de $count logs de plus de {$validated['days']} jours");

        return back()->with('success', "$count enregistrement(s) supprime(s).");
    }

    /**
     * Export logs.
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('causer');

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->get();
        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Date', 'Utilisateur', 'Log', 'Action', 'Description', 'Sujet']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    optional($log->created_at)->format('Y-m-d H:i:s'),
                    $log->user_name,
                    $log->log_name ?? '-',
                    $log->action ?? '-',
                    $log->description ?? '-',
                    class_basename($log->subject_type ?? '') ?: '-',
                ]);
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
