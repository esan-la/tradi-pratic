<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bibliography;
use Illuminate\Http\Request;

class BibliographyController extends Controller
{
    /**
     * Display the bibliography information
     */
    public function index()
    {
        // Il y a généralement qu'une seule entrée de bibliographie
        $bibliography = Bibliography::first();

        return view('admin.bibliography.index', compact('bibliography'));
    }

    /**
     * Show the form for editing the bibliography
     */
    public function edit()
    {
        $bibliography = Bibliography::firstOrNew();

        return view('admin.bibliography.edit', compact('bibliography'));
    }

    /**
     * Update the bibliography information
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'profile' => 'nullable|string',
            'parcours' => 'nullable|string',
            'experiences' => 'nullable|string',
        ]);

        $bibliography = Bibliography::firstOrNew();
        $bibliography->fill($validated);
        $bibliography->save();

        activity()
            ->performedOn($bibliography)
            ->causedBy(auth()->user())
            ->log('Mise à jour de la bibliographie');

        return redirect()->route('admin.bibliography.index')
            ->with('success', 'Bibliographie mise à jour avec succès.');
    }
}
