<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Démarrage du seeding...');
        $this->command->info('');

        // Ordre important : Permissions -> Roles -> Users
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            RealisationSeeder::class,  // Décommentez si vous créez ces seeders
            RecipeSeeder::class,
            AvailabilityperiodSeeder::class,
            EventSeeder::class,
            AppointmentSeeder::class,
            MediaVideoSeeder::class,
            MediaImageSeeder::class,
        ]);
        $this->command->newLine();
        $this->command->info('✅ Seeding terminé avec succès!');
        $this->command->info('📊 Base de données prête à l\'emploi');
        // $this->command->newLine();
        // $this->command->warn('🔐 Identifiants admin:');
        // $this->command->line('   Email: athanasesaw@gmail.com');
        // $this->command->line('   Mot de passe: admin@2025');
        // $this->command->warn('🔐 Identifiants de Test Gestionnaire:');
        // $this->command->line('   Email: test@test.com');
        // $this->command->line('   Mot de passe: 12345678');

        // $this->command->info('Utilisateurs créés avec succès!');
        $this->command->info('');
        $this->command->info('=== CREDENTIALS ===');
        $this->command->warn('Super Admin: superadmin@example.com / SuperAdmin@2026');
        $this->command->info('Admin: admin@example.com / Admin@2026');
        $this->command->info('Manager: manager@example.com / Manager@2026');
        $this->command->info('Receptionist: receptionist@example.com / Reception@2026');
        $this->command->info('Support: support@example.com / Support@2026');

        $this->command->info('');
        $this->command->info('✅ Seeding terminé avec succès!');
    }
}
