<?php
// database/seeders/AvailabilityPeriodSeeder.php

namespace Database\Seeders;

use App\Models\AvailabilityPeriod;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvailabilityPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ Récupérer l'admin via la table pivot (compatible UUID)
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        if (!$adminRoleId) {
            $this->command->warn('⚠️ Rôle admin introuvable. Exécutez d\'abord RoleSeeder.');
            return;
        }

        $adminUserId = DB::table('role_user')->where('role_id', $adminRoleId)->value('user_id');

        if (!$adminUserId) {
            // Fallback : prendre le super_admin
            $superAdminRoleId = DB::table('roles')->where('name', 'super_admin')->value('id');
            $adminUserId = DB::table('role_user')->where('role_id', $superAdminRoleId)->value('user_id');
        }

        if (!$adminUserId) {
            $this->command->warn('⚠️ Aucun administrateur trouvé. Exécutez d\'abord UserSeeder.');
            return;
        }

        $admin = User::find($adminUserId);

        if (!$admin) {
            $this->command->warn('⚠️ Utilisateur admin introuvable.');
            return;
        }

        $this->command->info("👤 Admin trouvé : {$admin->prenom} {$admin->nom} ({$admin->email})");

        $availabilities = [
            // ========================================
            // LUNDI (1)
            // ========================================
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

            // ========================================
            // MARDI (2)
            // ========================================
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

            // ========================================
            // MERCREDI (3)
            // ========================================
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

            // ========================================
            // JEUDI (4)
            // ========================================
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

            // ========================================
            // VENDREDI (5) - Plus court l'après-midi
            // ========================================
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
                'end_time' => '17:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => true,
            ],

            // ========================================
            // SAMEDI (6) - Matinée seulement
            // ========================================
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

            // ========================================
            // SPÉCIAL : Mardi soir (mois en cours)
            // ========================================
            [
                'admin_id' => $admin->id,
                'day_of_week' => 2,
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'is_recurring' => false,
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->endOfMonth()->toDateString(),
                'is_active' => true,
            ],

            // ========================================
            // DIMANCHE (0) - Désactivé
            // ========================================
            [
                'admin_id' => $admin->id,
                'day_of_week' => 0,
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'is_recurring' => true,
                'start_date' => null,
                'end_date' => null,
                'is_active' => false,
            ],
        ];

        $created = 0;
        foreach ($availabilities as $availability) {
            AvailabilityPeriod::create($availability);
            $created++;
        }

        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════════════════════╗');
        $this->command->info('║       ✅ DISPONIBILITÉS CRÉÉES AVEC SUCCÈS                  ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║                                                              ║');
        $this->command->info('║  📅 Total : ' . str_pad($created, 2, ' ', STR_PAD_LEFT) . ' périodes de disponibilité                    ║');
        $this->command->info('║                                                              ║');
        $this->command->info('║  📌 Horaires réguliers :                                     ║');
        $this->command->info('║     Lun-Jeu : 09h-12h & 14h-18h                             ║');
        $this->command->info('║     Vendredi : 09h-12h & 14h-17h                             ║');
        $this->command->info('║     Samedi   : 09h-13h                                       ║');
        $this->command->info('║     Dimanche : Fermé (désactivé)                             ║');
        $this->command->info('║                                                              ║');
        $this->command->info('║  🌙 Horaire spécial :                                        ║');
        $this->command->info('║     Mardi soir : 18h-20h (mois en cours)                     ║');
        $this->command->info('║                                                              ║');
        $this->command->info('╚══════════════════════════════════════════════════════════════╝');
    }
}
