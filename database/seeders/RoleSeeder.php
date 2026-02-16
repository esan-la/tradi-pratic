<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $roles = [
            [
                'name' => 'super_admin',
                'description' => 'Super Administrateur - Accès total au système',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'admin',
                'description' => 'Administrateur - Gestion complète sauf suppression critique',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'manager',
                'description' => 'Manager - Gestion du contenu de la plateforme',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'receptionist',
                'description' => 'Réceptionniste - Gestion des réservations et rendez-vous',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'customer_service',
                'description' => 'Service Client - Gestion des contacts et support',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('roles')->insert($roles);

        // Attribution des permissions aux rôles
        $this->assignPermissionsToRoles();

        $this->command->info('Rôles créés avec succès!');
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles(): void
    {
        // Récupérer tous les IDs
        $superAdminId = DB::table('roles')->where('name', 'super_admin')->value('id');
        $adminId = DB::table('roles')->where('name', 'admin')->value('id');
        $managerId = DB::table('roles')->where('name', 'manager')->value('id');
        $receptionistId = DB::table('roles')->where('name', 'receptionist')->value('id');
        $customerServiceId = DB::table('roles')->where('name', 'customer_service')->value('id');

        $allPermissions = DB::table('permissions')->pluck('id')->toArray();

        // ============================================
        // SUPER ADMIN - Toutes les permissions
        // ============================================
        foreach ($allPermissions as $permissionId) {
            DB::table('permission_role')->insert([
                'role_id' => $superAdminId,
                'permission_id' => $permissionId,
            ]);
        }

        // ============================================
        // ADMIN - Tout sauf suppressions critiques (users, roles)
        // ============================================
        $adminPermissions = DB::table('permissions')
            ->whereNotIn('name', ['users.delete', 'roles.delete', 'logs.clear'])
            ->pluck('id')
            ->toArray();

        foreach ($adminPermissions as $permissionId) {
            DB::table('permission_role')->insert([
                'role_id' => $adminId,
                'permission_id' => $permissionId,
            ]);
        }

        // ============================================
        // MANAGER - Gestion complète du contenu de la plateforme
        // ============================================
        $managerPermissions = DB::table('permissions')
            ->where(function($query) {
                $query
                    // Gestion des disponibilités (nouveau)
                    ->where('name', 'like', 'availabilities.%')
                    // Gestion des événements (nouveau)
                    ->orWhere('name', 'like', 'events.%')
                    // Gestion des rendez-vous
                    ->orWhere('name', 'like', 'appointments.%')
                    // Gestion des réalisations
                    ->orWhere('name', 'like', 'realisations.%')
                    // Gestion des recettes
                    ->orWhere('name', 'like', 'recipes.%')
                    // Gestion des témoignages
                    ->orWhere('name', 'like', 'testimonials.%')
                    // Gestion de la publicité de services
                    ->orWhere('name', 'like', 'pub-services.%')
                    // Gestion de la bibliographie
                    ->orWhere('name', 'like', 'bibliography.%')
                    // Gestion des produits
                    ->orWhere('name', 'like', 'products.%')
                    // Gestion des commandes
                    ->orWhere('name', 'like', 'orders.%')
                    // Gestion des hôtels
                    ->orWhere('name', 'like', 'hotels.%')
                    // Gestion des réservations
                    ->orWhere('name', 'like', 'reservations.%')
                    // Gestion des dons
                    ->orWhere('name', 'like', 'donations.%')
                    // Gestion des contacts
                    ->orWhere('name', 'like', 'contacts.%')
                    // Vue des paiements seulement
                    ->orWhere('name', 'payments.view');
            })
            ->pluck('id')
            ->toArray();

        foreach ($managerPermissions as $permissionId) {
            DB::table('permission_role')->insert([
                'role_id' => $managerId,
                'permission_id' => $permissionId,
            ]);
        }

        // ============================================
        // RECEPTIONIST - Gestion des hôtels, réservations et rendez-vous
        // ============================================
        $receptionistPermissions = DB::table('permissions')
            ->where(function($query) {
                $query
                    // Vue des disponibilités (consultation planning)
                    ->where('name', 'availabilities.view')
                    // Gestion des événements
                    ->orWhere('name', 'like', 'events.%')
                    // Gestion des rendez-vous
                    ->orWhere('name', 'like', 'appointments.%')
                    // Gestion des hôtels
                    ->orWhere('name', 'like', 'hotels.%')
                    // Gestion des réservations
                    ->orWhere('name', 'like', 'reservations.%')
                    // Vue et traitement des paiements
                    ->orWhere('name', 'like', 'payments.%');
            })
            ->pluck('id')
            ->toArray();

        foreach ($receptionistPermissions as $permissionId) {
            DB::table('permission_role')->insert([
                'role_id' => $receptionistId,
                'permission_id' => $permissionId,
            ]);
        }

        // ============================================
        // CUSTOMER SERVICE - Gestion des contacts uniquement
        // ============================================
        $customerServicePermissions = DB::table('permissions')
            ->where('name', 'like', 'contacts.%')
            ->pluck('id')
            ->toArray();

        foreach ($customerServicePermissions as $permissionId) {
            DB::table('permission_role')->insert([
                'role_id' => $customerServiceId,
                'permission_id' => $permissionId,
            ]);
        }

        $this->command->info('Permissions assignées aux rôles!');
        $this->command->info('');
        $this->command->info('=== HIÉRARCHIE DES RÔLES ===');
        $this->command->info('1. Super Admin: TOUTES les permissions (' . count($allPermissions) . ')');
        $this->command->info('2. Admin: Toutes sauf suppressions critiques (' . count($adminPermissions) . ')');
        $this->command->info('3. Manager: Gestion complète du contenu plateforme (' . count($managerPermissions) . ')');
        $this->command->info('   - Inclus: Disponibilités, Événements, Rendez-vous');
        $this->command->info('4. Receptionist: Hôtels, Réservations et Rendez-vous (' . count($receptionistPermissions) . ')');
        $this->command->info('   - Inclus: Vue disponibilités, Événements, Rendez-vous, Paiements');
        $this->command->info('5. Customer Service: Contacts uniquement (' . count($customerServicePermissions) . ')');
    }
}
