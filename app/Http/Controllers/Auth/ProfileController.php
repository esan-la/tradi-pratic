<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // protected MediaStorageService $mediaStorage;

    // /**
    //  * Injection du service MediaStorage
    //  */
    // public function __construct(MediaStorageService $mediaStorage)
    // {
    //     $this->mediaStorage = $mediaStorage;
    // }
    protected $mediaService;

    public function __construct(MediaStorageService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Afficher le profil
     */
    public function show()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    /**
     * Mettre à jour les informations personnelles
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nom'    => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email'  => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
        ], [
            'nom.required'    => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required'  => 'L\'email est obligatoire.',
            'email.email'     => 'Veuillez entrer un email valide.',
            'email.unique'    => 'Cette adresse email est déjà utilisée.',
        ]);

        $user->update($validated);

        return back()->with('success', 'Vos informations ont été mises à jour avec succès !');
    }

    /**
     * Mettre à jour l'avatar via MediaStorageService
     */
    public function updateAvatar(Request $request)
    {
        $validated = [];

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'avatar.required' => 'Veuillez sélectionner une image.',
            'avatar.image'    => 'Le fichier doit être une image.',
            'avatar.mimes'    => 'Formats acceptés : JPG, PNG, WebP.',
            'avatar.max'      => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        $user = Auth::user();

        // Supprimer l'ancien avatar via le service

        if ($user->avatar) {
            $this->mediaService->delete($user->avatar);
            $user->update(['avatar' => null]);
        }
        // Upload avatar si fourni
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar
            if ($user->avatar) {
                $this->mediaService->delete($user->avatar);
            }

            $validated['avatar'] = $this->mediaService->uploadImage(
                $request->file('avatar'),
                'avatars'
            );
        }

        $user->update($validated);

        return back()->with('success', 'Votre photo de profil a été mise à jour !');
    }

    /**
     * Supprimer l'avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            $this->mediaService->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Votre photo de profil a été supprimée.');
    }

    /**
     * Changer le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed|different:current_password',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required'         => 'Le nouveau mot de passe est obligatoire.',
            'password.min'              => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'        => 'Les mots de passe ne correspondent pas.',
            'password.different'        => 'Le nouveau mot de passe doit être différent de l\'ancien.',
        ]);

        $user = Auth::user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('password_success', 'Votre mot de passe a été changé avec succès !');
    }
    /**
     * Supprimer le compte connecte.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Veuillez saisir votre mot de passe.',
            'password.current_password' => 'Le mot de passe est incorrect.',
        ]);

        $user = Auth::user();

        if ($user->avatar) {
            $this->mediaService->delete($user->avatar);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('home')->with('success', 'Votre compte a ete supprime avec succes.');
    }
}
