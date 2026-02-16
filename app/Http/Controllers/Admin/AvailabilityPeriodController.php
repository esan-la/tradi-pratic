<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailabilityPeriod;
use App\Models\User;
use Illuminate\Http\Request;

class AvailabilityPeriodController extends Controller
{
    /**
     * Display a listing of availability periods
     */
    public function index(Request $request)
    {
        $query = AvailabilityPeriod::with('admin');

        // Filtre par administrateur
        if ($request->has('admin_id') && $request->admin_id != '') {
            $query->where('admin_id', $request->admin_id);
        }

        // Filtre par jour de la semaine
        if ($request->has('day_of_week') && $request->day_of_week !== '') {
            $query->where('day_of_week', $request->day_of_week);
        }

        // Filtre par statut
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        $availabilities = $query->orderBy('day_of_week')
                               ->orderBy('start_time')
                               ->paginate(15);

        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();

        $daysOfWeek = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];

        return view('admin.availabilities.index', compact('availabilities', 'admins', 'daysOfWeek'));
    }

    /**
     * Show the form for creating a new availability period
     */
    public function create()
    {
        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();

        $daysOfWeek = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];

        return view('admin.availabilities.create', compact('admins', 'daysOfWeek'));
    }

    /**
     * Store a newly created availability period
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:users,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_recurring' => 'boolean',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        // Gérer les checkboxes
        $validated['is_recurring'] = $request->has('is_recurring') && $request->is_recurring == '1';
        $validated['is_active'] = $request->has('is_active') && $request->is_active == '1';

        // Vérifier les chevauchements
        $hasOverlap = AvailabilityPeriod::where('admin_id', $validated['admin_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->active()
            ->where(function($q) use ($validated) {
                $q->where(function($q2) use ($validated) {
                    $q2->where('start_time', '<', $validated['end_time'])
                       ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->exists();

        if ($hasOverlap) {
            return back()->withInput()
                ->with('error', 'Cette disponibilité chevauche une disponibilité existante pour ce jour.');
        }

        $availability = AvailabilityPeriod::create($validated);

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($availability)
                    ->causedBy(auth()->user())
                    ->log('Création de disponibilité : ' . $availability->day_name . ' ' . $availability->time_range);
            }
        } catch (\Exception $e) {
            // Ignorer si activity log n'est pas disponible
        }

        return redirect()->route('admin.availabilities.index')
            ->with('success', 'Disponibilité créée avec succès.');
    }

    /**
     * Display the specified availability period
     */
    public function show(AvailabilityPeriod $availability)
    {
        $availability->load(['admin', 'events']);
        return view('admin.availabilities.show', compact('availability'));
    }

    /**
     * Show the form for editing the specified availability period
     */
    public function edit(AvailabilityPeriod $availability)
    {
        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();

        $daysOfWeek = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];

        return view('admin.availabilities.edit', compact('availability', 'admins', 'daysOfWeek'));
    }

    /**
     * Update the specified availability period
     */
    public function update(Request $request, AvailabilityPeriod $availability)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:users,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_recurring' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        // Gérer les checkboxes
        $validated['is_recurring'] = $request->has('is_recurring') && $request->is_recurring == '1';
        $validated['is_active'] = $request->has('is_active') && $request->is_active == '1';

        // Vérifier les chevauchements (sauf cette disponibilité)
        $hasOverlap = AvailabilityPeriod::where('admin_id', $validated['admin_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $availability->id)
            ->active()
            ->where(function($q) use ($validated) {
                $q->where(function($q2) use ($validated) {
                    $q2->where('start_time', '<', $validated['end_time'])
                       ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->exists();

        if ($hasOverlap) {
            return back()->withInput()
                ->with('error', 'Cette disponibilité chevauche une disponibilité existante pour ce jour.');
        }

        $availability->update($validated);

        // Logger l'activité
        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($availability)
                    ->causedBy(auth()->user())
                    ->log('Modification de disponibilité : ' . $availability->day_name . ' ' . $availability->time_range);
            }
        } catch (\Exception $e) {
            // Ignorer
        }

        return redirect()->route('admin.availabilities.show', $availability)
            ->with('success', 'Disponibilité mise à jour avec succès.');
    }

    /**
     * Toggle active status
     */
    public function toggle(AvailabilityPeriod $availability)
    {
        $availability->update([
            'is_active' => !$availability->is_active
        ]);

        $status = $availability->is_active ? 'activée' : 'désactivée';

        try {
            if (function_exists('activity')) {
                activity()
                    ->performedOn($availability)
                    ->causedBy(auth()->user())
                    ->log("Disponibilité {$status} : " . $availability->day_name);
            }
        } catch (\Exception $e) {
            // Ignorer
        }

        return back()->with('success', "Disponibilité {$status} avec succès.");
    }

    /**
     * Remove the specified availability period
     */
    public function destroy(AvailabilityPeriod $availability)
    {
        $description = $availability->day_name . ' ' . $availability->time_range;

        // Vérifier s'il y a des événements liés
        if ($availability->events()->exists()) {
            return back()->with('error', 'Impossible de supprimer cette disponibilité car elle est liée à des événements.');
        }

        $availability->delete();

        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->log('Suppression de disponibilité : ' . $description);
            }
        } catch (\Exception $e) {
            // Ignorer
        }

        return redirect()->route('admin.availabilities.index')
            ->with('success', 'Disponibilité supprimée avec succès.');
    }

    /**
     * Duplicate a week's availability to another week
     */
    public function duplicate(Request $request)
    {
        $validated = $request->validate([
            'source_admin_id' => 'required|exists:users,id',
            'target_admin_id' => 'required|exists:users,id',
            'source_week' => 'required|date',
            'target_week' => 'required|date|after:source_week',
        ]);

        try {
            \DB::beginTransaction();

            $sourceAvailabilities = AvailabilityPeriod::where('admin_id', $validated['source_admin_id'])
                ->active()
                ->get();

            $count = 0;
            foreach ($sourceAvailabilities as $source) {
                AvailabilityPeriod::create([
                    'admin_id' => $validated['target_admin_id'],
                    'day_of_week' => $source->day_of_week,
                    'start_time' => $source->start_time,
                    'end_time' => $source->end_time,
                    'is_recurring' => $source->is_recurring,
                    'start_date' => $validated['target_week'],
                    'end_date' => $source->end_date,
                    'is_active' => true,
                ]);
                $count++;
            }

            \DB::commit();

            return back()->with('success', "{$count} disponibilités dupliquées avec succès.");

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Erreur lors de la duplication : ' . $e->getMessage());
        }
    }
}
