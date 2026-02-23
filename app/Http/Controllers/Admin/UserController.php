<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Filtre par rôle
        if ($request->has('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // ✅ Corrigé : nom + prenom au lieu de name
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // ✅ Corrigé : nom et prenom séparés
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'nullable|string|max:255',
            'password' => ['required', 'confirmed', Password::min(8)],
            // ✅ Ajouté : avatar optionnel
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        // ✅ Gestion de l'avatar
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'avatar' => $avatarPath,
        ]);

        // Assigner les rôles
        $user->roles()->sync($validated['roles']);

        // ✅ Corrigé : utiliser le nom complet
        $fullName = $user->prenom . ' ' . $user->nom;

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log('Création de l\'utilisateur : ' . $fullName);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

//     // Create : Required
// $mediaService->uploadImage($file, 'pub-services')

// // Update : Optional (conservation possible)
// if ($request->hasFile('image')) {
//     // Supprimer ancienne
//     $mediaService->delete($old);
//     // Upload nouvelle
// }

// // Delete : Nettoyage
// $mediaService->delete($image)

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['roles.permissions', 'activityLogs' => function($query) {
            $query->latest()->limit(20);
        }]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            // ✅ Corrigé : nom et prenom séparés
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'password' => ['nullable', 'confirmed', Password::min(8)],
            // ✅ Ajouté : avatar optionnel
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        $updateData = [
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        // ✅ Gestion de l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar si existe
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $updateData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Mettre à jour le mot de passe si fourni
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Mettre à jour les rôles
        $user->roles()->sync($validated['roles']);

        // ✅ Corrigé : utiliser le nom complet
        $fullName = $user->prenom . ' ' . $user->nom;

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log('Modification de l\'utilisateur : ' . $fullName);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // ✅ Corrigé : utiliser le nom complet
        $fullName = $user->prenom . ' ' . $user->nom;

        // ✅ Supprimer l'avatar si existe
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Suppression de l\'utilisateur : ' . $fullName);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Toggle user status (si vous avez un champ is_active)
     */
    public function toggleStatus(User $user)
    {
        // ⚠️ Note: Votre migration n'a pas de champ is_active
        // Si vous voulez cette fonctionnalité, ajoutez le champ à la migration
        if (!Schema::hasColumn('users', 'is_active')) {
            return back()->with('error', 'La fonctionnalité de statut n\'est pas disponible.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activé' : 'désactivé';
        $fullName = $user->prenom . ' ' . $user->nom;

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log("Utilisateur {$fullName} {$status}");

        return back()->with('success', "Utilisateur {$status} avec succès.");
    }

    /**
     * ✅ NOUVEAU : Supprimer l'avatar
     */
    public function deleteAvatar(User $user)
    {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);

            return back()->with('success', 'Avatar supprimé avec succès.');
        }

        return back()->with('error', 'Aucun avatar à supprimer.');
    }
}
