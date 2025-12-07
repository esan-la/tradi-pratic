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
        $this->call([
            UserSeeder::class,
            RealisationSeeder::class,  // Décommentez si vous créez ces seeders
            RecipeSeeder::class,
            AppointmentSeeder::class,
        ]);
        $this->command->newLine();
        $this->command->info('✅ Seeding terminé avec succès!');
        $this->command->info('📊 Base de données prête à l\'emploi');
        $this->command->newLine();
        $this->command->warn('🔐 Identifiants admin:');
        $this->command->line('   Email: athanasesaw@gmail.com');
        $this->command->line('   Mot de passe: admin@2025');
        $this->command->warn('🔐 Identifiants de Test Gestionnaire:');
        $this->command->line('   Email: test@test.com');
        $this->command->line('   Mot de passe: 12345678');
    }
}
