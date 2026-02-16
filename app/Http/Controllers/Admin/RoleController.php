<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->get();
        return view('admin.users.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('admin.users.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        // Assigner les permissions
        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        activity()
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->log('Création du rôle : ' . $role->name);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);

        $permissionsByGroup = $role->permissions->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('admin.users.roles.show', compact('role', 'permissionsByGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role->load('permissions');

        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('admin.users.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);

        $role->update($validated);

        activity()
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->log('Modification du rôle : ' . $role->name);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    /**
     * Update role permissions
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        activity()
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->log('Mise à jour des permissions du rôle : ' . $role->name);

        return back()->with('success', 'Permissions mises à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Empêcher la suppression des rôles système
        if (in_array($role->name, ['super_admin', 'admin'])) {
            return back()->with('error', 'Vous ne pouvez pas supprimer ce rôle système.');
        }

        // Vérifier si des utilisateurs ont ce rôle
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Ce rôle est assigné à des utilisateurs. Veuillez d\'abord les réassigner.');
        }

        $name = $role->name;
        $role->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Suppression du rôle : ' . $name);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }
}
