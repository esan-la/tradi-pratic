<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🌱 Démarrage du seeding...');
        $this->command->info('');

        // ⚠️ ORDRE IMPORTANT : Permissions → Rôles → Utilisateurs
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            AvailabilityperiodSeeder::class,
            EventSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('=== CREDENTIALS ===');
        $this->command->warn('Super Admin: superadmin@example.com / SuperAdmin@2026');
        $this->command->info('Admin: admin@example.com / Admin@2026');
        $this->command->info('Manager: manager@example.com / Manager@2026');
        $this->command->info('Receptionist: receptionist@example.com / Reception@2026');
        $this->command->info('Support: support@example.com / Support@2026');
        $this->command->info('🎉 Seeding terminé avec succès !');
        $this->command->info('');
    }
}
