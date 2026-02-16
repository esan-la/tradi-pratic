<?php

namespace Database\Seeders;

use App\Models\AvailabilityPeriod;
use App\Models\User;
use Illuminate\Database\Seeder;

class AvailabilityPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le premier admin
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->first();

        if (!$admin) {
            $this->command->warn('⚠️ Aucun administrateur trouvé. Créer d\'abord les utilisateurs.');
            return;
        }

        $availabilities = [
            // LUNDI (1)
            [
                'admin_id' => $admin->id,
                'day_of_week' => 1,
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'admin_id' => $admin->id,
                'day_of_week' => 1,
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],

            // MARDI (2)
            [
                'admin_id' => $admin->id,
                'day_of_week' => 2,
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'admin_id' => $admin->id,
                'day_of_week' => 2,
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],

            // MERCREDI (3)
            [
                'admin_id' => $admin->id,
                'day_of_week' => 3,
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'admin_id' => $admin->id,
                'day_of_week' => 3,
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],

            // JEUDI (4)
            [
                'admin_id' => $admin->id,
                'day_of_week' => 4,
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'admin_id' => $admin->id,
                'day_of_week' => 4,
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],

            // VENDREDI (5)
            [
                'admin_id' => $admin->id,
                'day_of_week' => 5,
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],
            [
                'admin_id' => $admin->id,
                'day_of_week' => 5,
                'start_time' => '14:00:00',
                'end_time' => '17:00:00', // Plus court le vendredi
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],

            // SAMEDI (6) - Matinée seulement
            [
                'admin_id' => $admin->id,
                'day_of_week' => 6,
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],

            // Disponibilité spéciale pour un mois spécifique (exemple)
            [
                'admin_id' => $admin->id,
                'day_of_week' => 2, // Mardi
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'is_recurring' => false,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'is_active' => true,
            ],

            // Disponibilité désactivée (exemple)
            [
                'admin_id' => $admin->id,
                'day_of_week' => 0, // Dimanche
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => false, // Désactivé
            ],
        ];

        foreach ($availabilities as $availability) {
            AvailabilityPeriod::create($availability);
        }

        $this->command->info('✅ ' . count($availabilities) . ' périodes de disponibilité créées avec succès!');
        $this->command->info('📅 Horaires: Lun-Jeu: 9h-12h & 14h-18h | Ven: 9h-12h & 14h-17h | Sam: 9h-13h');
    }
}
