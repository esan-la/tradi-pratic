<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    protected $mediaService;

    public function __construct(MediaStorageService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = auth()->user();
        $user->load('roles.permissions');

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Upload avatar si fourni
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar
            if ($user->avatar) {
                $this->mediaService->delete($user->avatar);
            }

            $validated['avatar'] = $this->mediaService->uploadImage(
                $request->file('avatar'),
                'users/avatars'
            );
        }

        $user->update($validated);

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->log('Mise à jour du profil');

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->log('Changement de mot de passe');

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar()
    {
        $user = auth()->user();

        if ($user->avatar) {
            $this->mediaService->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Avatar supprimé avec succès.');
    }
}
