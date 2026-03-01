<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $users = [
            [
                'id' => Str::uuid()->toString(),
                'nom' => 'Super Admin',
                'prenom' => 'Athanase',
                'email' => 'superadmin@example.com',
                'phone' => '+226 70 00 00 00',
                'email_verified_at' => $now,
                'password' => Hash::make('SuperAdmin@2026'),
                'avatar' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid()->toString(),
                'nom' => 'Administrateur Principal',
                'prenom' => 'Rakis',
                'email' => 'admin@example.com',
                'phone' => '+226 70 00 00 01',
                'email_verified_at' => $now,
                'password' => Hash::make('Admin@2026'),
                'avatar' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid()->toString(),
                'nom' => 'Gestionnaire',
                'prenom' => 'Adama',
                'email' => 'manager@example.com',
                'phone' => '+226 70 00 00 02',
                'email_verified_at' => $now,
                'password' => Hash::make('Manager@2026'),
                'avatar' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid()->toString(),
                'nom' => 'Réceptionniste',
                'prenom' => 'Zonabo',
                'email' => 'receptionist@example.com',
                'phone' => '+226 70 00 00 03',
                'email_verified_at' => $now,
                'password' => Hash::make('Reception@2026'),
                'avatar' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Str::uuid()->toString(),
                'nom' => 'Service Client',
                'prenom' => 'Adjeratou',
                'email' => 'support@example.com',
                'phone' => '+226 70 00 00 04',
                'email_verified_at' => $now,
                'password' => Hash::make('Support@2026'),
                'avatar' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('users')->insert($users);

        // Attribution des rôles aux utilisateurs
        $this->assignRolesToUsers();

        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════════════════════╗');
        $this->command->info('║              ✅ UTILISATEURS CRÉÉS AVEC SUCCÈS              ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║                                                              ║');
        $this->command->info('║  👑 Super Admin                                              ║');
        $this->command->info('║     Email : superadmin@example.com                           ║');
        $this->command->info('║     MDP   : SuperAdmin@2026                                  ║');
        $this->command->info('║                                                              ║');
        $this->command->info('║  🛡️  Admin                                                    ║');
        $this->command->info('║     Email : admin@example.com                                ║');
        $this->command->info('║     MDP   : Admin@2026                                       ║');
        $this->command->info('║                                                              ║');
        $this->command->info('║  📋 Manager                                                  ║');
        $this->command->info('║     Email : manager@example.com                              ║');
        $this->command->info('║     MDP   : Manager@2026                                     ║');
        $this->command->info('║                                                              ║');
        $this->command->info('║  🏨 Réceptionniste                                           ║');
        $this->command->info('║     Email : receptionist@example.com                         ║');
        $this->command->info('║     MDP   : Reception@2026                                   ║');
        $this->command->info('║                                                              ║');
        $this->command->info('║  📞 Service Client                                           ║');
        $this->command->info('║     Email : support@example.com                              ║');
        $this->command->info('║     MDP   : Support@2026                                     ║');
        $this->command->info('║                                                              ║');
        $this->command->info('╚══════════════════════════════════════════════════════════════╝');
        $this->command->info('');
        $this->command->warn('⚠️  Changez ces mots de passe en production !');
    }

    /**
     * Assign roles to users
     */
    private function assignRolesToUsers(): void
    {
        // Récupérer les IDs des utilisateurs
        $superAdminUser = DB::table('users')->where('email', 'superadmin@example.com')->value('id');
        $adminUser = DB::table('users')->where('email', 'admin@example.com')->value('id');
        $managerUser = DB::table('users')->where('email', 'manager@example.com')->value('id');
        $receptionistUser = DB::table('users')->where('email', 'receptionist@example.com')->value('id');
        $supportUser = DB::table('users')->where('email', 'support@example.com')->value('id');

        // Récupérer les IDs des rôles
        $superAdminRole = DB::table('roles')->where('name', 'super_admin')->value('id');
        $adminRole = DB::table('roles')->where('name', 'admin')->value('id');
        $managerRole = DB::table('roles')->where('name', 'manager')->value('id');
        $receptionistRole = DB::table('roles')->where('name', 'receptionist')->value('id');
        $customerServiceRole = DB::table('roles')->where('name', 'customer_service')->value('id');

        // Vérification avant insertion
        if (!$superAdminUser || !$superAdminRole) {
            $this->command->error('❌ Erreur: Utilisateurs ou rôles introuvables !');
            return;
        }

        // Assigner les rôles
        $roleAssignments = [
            ['user_id' => $superAdminUser, 'role_id' => $superAdminRole],
            ['user_id' => $adminUser, 'role_id' => $adminRole],
            ['user_id' => $managerUser, 'role_id' => $managerRole],
            ['user_id' => $receptionistUser, 'role_id' => $receptionistRole],
            ['user_id' => $supportUser, 'role_id' => $customerServiceRole],
        ];

        DB::table('role_user')->insert($roleAssignments);

        $this->command->info('✅ Rôles assignés aux utilisateurs!');
    }
}
