<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        activity()
            ->causedBy(auth()->user())
            ->log('Mise à jour des paramètres système');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }

    /**
     * Get a setting value
     */
    public function get(string $key)
    {
        $setting = Setting::where('key', $key)->first();

        return response()->json([
            'key' => $key,
            'value' => $setting ? $setting->value : null,
        ]);
    }

    /**
     * Set a setting value
     */
    public function set(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'nullable|string',
            'type' => 'nullable|string|in:string,integer,boolean,json',
            'group' => 'nullable|string|max:255',
        ]);

        $setting = Setting::updateOrCreate(
            ['key' => $validated['key']],
            [
                'value' => $validated['value'],
                'type' => $validated['type'] ?? 'string',
                'group' => $validated['group'] ?? 'general',
            ]
        );

        return response()->json([
            'success' => true,
            'setting' => $setting,
        ]);
    }
}
