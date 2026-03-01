<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
                'id' => Str::uuid()->toString(),
                'name' => 'super_admin',
                'description' => 'Super Administrateur - Accès total au système',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'admin',
                'description' => 'Administrateur - Gestion complète sauf suppression critique',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manager',
                'description' => 'Manager - Gestion du contenu de la plateforme',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'receptionist',
                'description' => 'Réceptionniste - Gestion des réservations et rendez-vous',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'customer_service',
                'description' => 'Service Client - Gestion des contacts et support',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('roles')->insert($roles);

        // Attribution des permissions aux rôles
        $this->assignPermissionsToRoles();

        $this->command->info('✅ Rôles créés avec succès!');
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles(): void
    {
        // Récupérer tous les IDs des rôles
        $superAdminId = DB::table('roles')->where('name', 'super_admin')->value('id');
        $adminId = DB::table('roles')->where('name', 'admin')->value('id');
        $managerId = DB::table('roles')->where('name', 'manager')->value('id');
        $receptionistId = DB::table('roles')->where('name', 'receptionist')->value('id');
        $customerServiceId = DB::table('roles')->where('name', 'customer_service')->value('id');

        $allPermissions = DB::table('permissions')->pluck('id')->toArray();

        // ============================================
        // SUPER ADMIN - Toutes les permissions
        // ============================================
        $superAdminData = [];
        foreach ($allPermissions as $permissionId) {
            $superAdminData[] = [
                'role_id' => $superAdminId,
                'permission_id' => $permissionId,
            ];
        }
        // ✅ Insert par chunks pour performance
        foreach (array_chunk($superAdminData, 50) as $chunk) {
            DB::table('permission_role')->insert($chunk);
        }

        // ============================================
        // ADMIN - Tout sauf suppressions critiques
        // ============================================
        $adminPermissions = DB::table('permissions')
            ->whereNotIn('name', ['users.delete', 'roles.delete', 'logs.clear'])
            ->pluck('id')
            ->toArray();

        $adminData = [];
        foreach ($adminPermissions as $permissionId) {
            $adminData[] = [
                'role_id' => $adminId,
                'permission_id' => $permissionId,
            ];
        }
        foreach (array_chunk($adminData, 50) as $chunk) {
            DB::table('permission_role')->insert($chunk);
        }

        // ============================================
        // MANAGER - Gestion complète du contenu
        // ============================================
        $managerPermissions = DB::table('permissions')
            ->where(function ($query) {
                $query
                    ->where('name', 'like', 'availabilities.%')
                    ->orWhere('name', 'like', 'events.%')
                    ->orWhere('name', 'like', 'appointments.%')
                    ->orWhere('name', 'like', 'realisations.%')
                    ->orWhere('name', 'like', 'recipes.%')
                    ->orWhere('name', 'like', 'testimonials.%')
                    ->orWhere('name', 'like', 'pub-services.%')
                    ->orWhere('name', 'like', 'bibliography.%')
                    ->orWhere('name', 'like', 'media_images.%')
                    ->orWhere('name', 'like', 'media_videos.%')
                    ->orWhere('name', 'like', 'products.%')
                    ->orWhere('name', 'like', 'orders.%')
                    ->orWhere('name', 'like', 'hotels.%')
                    ->orWhere('name', 'like', 'reservations.%')
                    ->orWhere('name', 'like', 'donations.%')
                    ->orWhere('name', 'like', 'contacts.%')
                    ->orWhere('name', 'like', 'livestreams.%')
                    ->orWhere('name', 'like', 'social-links.%')
                    ->orWhere('name', '=', 'payments.view')
                    ->orWhere('name', '=', 'dashboard.view')
                    ->orWhere('name', '=', 'dashboard.stats');
            })
            ->pluck('id')
            ->toArray();

        $managerData = [];
        foreach ($managerPermissions as $permissionId) {
            $managerData[] = [
                'role_id' => $managerId,
                'permission_id' => $permissionId,
            ];
        }
        foreach (array_chunk($managerData, 50) as $chunk) {
            DB::table('permission_role')->insert($chunk);
        }

        // ============================================
        // RECEPTIONIST - Hôtels, Réservations, RDV
        // ============================================
        $receptionistPermissions = DB::table('permissions')
            ->where(function ($query) {
                $query
                    ->where('name', '=', 'availabilities.view')
                    ->orWhere('name', 'like', 'events.%')
                    ->orWhere('name', 'like', 'appointments.%')
                    ->orWhere('name', 'like', 'hotels.%')
                    ->orWhere('name', 'like', 'reservations.%')
                    ->orWhere('name', 'like', 'payments.%')
                    ->orWhere('name', '=', 'dashboard.view');
            })
            ->pluck('id')
            ->toArray();

        $receptionistData = [];
        foreach ($receptionistPermissions as $permissionId) {
            $receptionistData[] = [
                'role_id' => $receptionistId,
                'permission_id' => $permissionId,
            ];
        }
        foreach (array_chunk($receptionistData, 50) as $chunk) {
            DB::table('permission_role')->insert($chunk);
        }

        // ============================================
        // CUSTOMER SERVICE - Contacts + Témoignages
        // ============================================
        $customerServicePermissions = DB::table('permissions')
            ->where(function ($query) {
                $query
                    ->where('name', 'like', 'contacts.%')
                    ->orWhere('name', 'like', 'testimonials.%')
                    ->orWhere('name', '=', 'dashboard.view');
            })
            ->pluck('id')
            ->toArray();

        $customerServiceData = [];
        foreach ($customerServicePermissions as $permissionId) {
            $customerServiceData[] = [
                'role_id' => $customerServiceId,
                'permission_id' => $permissionId,
            ];
        }
        foreach (array_chunk($customerServiceData, 50) as $chunk) {
            DB::table('permission_role')->insert($chunk);
        }

        // ============================================
        // AFFICHAGE RÉCAPITULATIF
        // ============================================
        $this->command->info('');
        $this->command->info('✅ Permissions assignées aux rôles!');
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════════╗');
        $this->command->info('║          HIÉRARCHIE DES RÔLES                   ║');
        $this->command->info('╠══════════════════════════════════════════════════╣');
        $this->command->info('║ 1. 👑 Super Admin    : ' . str_pad(count($allPermissions), 3, ' ', STR_PAD_LEFT) . ' permissions (TOUTES)  ║');
        $this->command->info('║ 2. 🛡️  Admin          : ' . str_pad(count($adminPermissions), 3, ' ', STR_PAD_LEFT) . ' permissions           ║');
        $this->command->info('║ 3. 📋 Manager        : ' . str_pad(count($managerPermissions), 3, ' ', STR_PAD_LEFT) . ' permissions           ║');
        $this->command->info('║ 4. 🏨 Receptionist   : ' . str_pad(count($receptionistPermissions), 3, ' ', STR_PAD_LEFT) . ' permissions           ║');
        $this->command->info('║ 5. 📞 Customer Svc   : ' . str_pad(count($customerServicePermissions), 3, ' ', STR_PAD_LEFT) . ' permissions           ║');
        $this->command->info('╚══════════════════════════════════════════════════╝');
        $this->command->info('');
        $this->command->info('📌 Manager inclut :');
        $this->command->info('   • Disponibilités, Événements, Rendez-vous');
        $this->command->info('   • Réalisations, Recettes, Témoignages');
        $this->command->info('   • Pub-Services, Bibliographie');
        $this->command->info('   • Images & Vidéos (Médias)');
        $this->command->info('   • Produits, Commandes, Hôtels, Réservations');
        $this->command->info('   • Dons, Contacts, Lives, Liens sociaux');
        $this->command->info('   • Dashboard (vue + stats)');
        $this->command->info('');
        $this->command->info('📌 Receptionist inclut :');
        $this->command->info('   • Vue disponibilités, Événements');
        $this->command->info('   • Rendez-vous, Hôtels, Réservations');
        $this->command->info('   • Paiements, Dashboard');
        $this->command->info('');
        $this->command->info('📌 Customer Service inclut :');
        $this->command->info('   • Contacts, Témoignages, Dashboard');
    }
}
