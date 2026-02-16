<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Models\AvailabilityPeriod;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
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

        // Récupérer une disponibilité pour lier certains événements
        $availability = AvailabilityPeriod::where('admin_id', $admin->id)
            ->where('is_active', true)
            ->first();

        $events = [
            // ÉVÉNEMENTS PASSÉS - Rendez-vous (liés aux appointments)
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Amadou Traoré',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->subDays(15)->setTime(9, 0, 0),
                'end_datetime' => Carbon::now()->subDays(15)->setTime(9, 30, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Consultation traditionnelle',
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Fatoumata Ouédraogo',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->subDays(10)->setTime(14, 30, 0),
                'end_datetime' => Carbon::now()->subDays(10)->setTime(15, 0, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Soins naturels',
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(12),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Ibrahim Sawadogo',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->subDays(7)->setTime(10, 0, 0),
                'end_datetime' => Carbon::now()->subDays(7)->setTime(10, 30, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Prière',
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(9),
            ],

            // ÉVÉNEMENTS PASSÉS - Travail quotidien
            [
                'admin_id' => $admin->id,
                'title' => 'Préparation remèdes traditionnels',
                'event_type' => 'daily_work',
                'start_datetime' => Carbon::now()->subDays(5)->setTime(8, 0, 0),
                'end_datetime' => Carbon::now()->subDays(5)->setTime(9, 0, 0),
                'availability_period_id' => null,
                'description' => 'Préparation des plantes médicinales pour la semaine',
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(6),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Cérémonie de purification du cabinet',
                'event_type' => 'daily_work',
                'start_datetime' => Carbon::now()->subDays(3)->setTime(7, 30, 0),
                'end_datetime' => Carbon::now()->subDays(3)->setTime(8, 30, 0),
                'availability_period_id' => null,
                'description' => 'Rituel hebdomadaire de purification',
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(4),
            ],

            // ÉVÉNEMENTS PASSÉS - Réunion
            [
                'admin_id' => $admin->id,
                'title' => 'Réunion avec fournisseurs de plantes',
                'event_type' => 'meeting',
                'start_datetime' => Carbon::now()->subDays(8)->setTime(15, 0, 0),
                'end_datetime' => Carbon::now()->subDays(8)->setTime(17, 0, 0),
                'availability_period_id' => null,
                'description' => 'Discussion sur les nouvelles plantes médicinales disponibles',
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(10),
            ],

            // ÉVÉNEMENTS FUTURS - Rendez-vous confirmés
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Mariam Compaoré',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->addDays(2)->setTime(9, 30, 0),
                'end_datetime' => Carbon::now()->addDays(2)->setTime(10, 0, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Consultation spirituelle',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Souleymane Kaboré',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->addDays(3)->setTime(15, 0, 0),
                'end_datetime' => Carbon::now()->addDays(3)->setTime(15, 30, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Consultation traditionnelle',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Aïssata Diallo',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->addDays(5)->setTime(11, 0, 0),
                'end_datetime' => Carbon::now()->addDays(5)->setTime(11, 30, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Soins naturels - Suivi',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subDays(2),
            ],

            // ÉVÉNEMENTS FUTURS - Rendez-vous en attente
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Moussa Ouattara',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->addDays(7)->setTime(8, 0, 0),
                'end_datetime' => Carbon::now()->addDays(7)->setTime(8, 30, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Prière',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Rasmata Zongo',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->addDays(8)->setTime(16, 0, 0),
                'end_datetime' => Carbon::now()->addDays(8)->setTime(16, 30, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Consultation spirituelle',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subHours(12),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Boukary Yaméogo',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->addDays(10)->setTime(13, 30, 0),
                'end_datetime' => Carbon::now()->addDays(10)->setTime(14, 0, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Consultation traditionnelle',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subHours(6),
            ],

            // AUJOURD'HUI ET DEMAIN
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Adama Sanogo',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->setTime(17, 0, 0),
                'end_datetime' => Carbon::now()->setTime(17, 30, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Consultation traditionnelle - Urgente',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Zénabo Bassolé',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->addDay()->setTime(9, 0, 0),
                'end_datetime' => Carbon::now()->addDay()->setTime(9, 30, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Prière',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subDays(3),
            ],

            // ÉVÉNEMENTS FUTURS - Travail quotidien
            [
                'admin_id' => $admin->id,
                'title' => 'Préparation hebdomadaire',
                'event_type' => 'daily_work',
                'start_datetime' => Carbon::now()->addDays(1)->setTime(8, 0, 0),
                'end_datetime' => Carbon::now()->addDays(1)->setTime(9, 0, 0),
                'availability_period_id' => null,
                'description' => 'Préparation des outils et matériels pour la semaine',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'admin_id' => $admin->id,
                'title' => 'Méditation et prières matinales',
                'event_type' => 'daily_work',
                'start_datetime' => Carbon::now()->addDays(4)->setTime(7, 0, 0),
                'end_datetime' => Carbon::now()->addDays(4)->setTime(8, 0, 0),
                'availability_period_id' => null,
                'description' => 'Rituel spirituel quotidien',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subDays(2),
            ],

            // ÉVÉNEMENTS FUTURS - Réunion
            [
                'admin_id' => $admin->id,
                'title' => 'Formation sur nouvelles pratiques',
                'event_type' => 'meeting',
                'start_datetime' => Carbon::now()->addDays(6)->setTime(14, 0, 0),
                'end_datetime' => Carbon::now()->addDays(6)->setTime(16, 0, 0),
                'availability_period_id' => null,
                'description' => 'Session de formation avec des praticiens traditionnels',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subDays(3),
            ],

            // ÉVÉNEMENTS ANNULÉS
            [
                'admin_id' => $admin->id,
                'title' => 'Consultation - Annulée',
                'event_type' => 'appointment',
                'start_datetime' => Carbon::now()->addDays(4)->setTime(10, 0, 0),
                'end_datetime' => Carbon::now()->addDays(4)->setTime(10, 30, 0),
                'availability_period_id' => $availability?->id,
                'description' => 'Client a annulé',
                'status' => 'cancelled',
                'created_at' => Carbon::now()->subDays(7),
            ],

            // ÉVÉNEMENTS AUTRES
            [
                'admin_id' => $admin->id,
                'title' => 'Cérémonie communautaire',
                'event_type' => 'other',
                'start_datetime' => Carbon::now()->addDays(12)->setTime(10, 0, 0),
                'end_datetime' => Carbon::now()->addDays(12)->setTime(14, 0, 0),
                'availability_period_id' => null,
                'description' => 'Participation à une cérémonie traditionnelle dans le village',
                'status' => 'scheduled',
                'created_at' => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }

        $this->command->info('✅ ' . count($events) . ' événements créés avec succès!');
        $this->command->info('📊 Répartition:');
        $this->command->info('   - Appointments: ' . collect($events)->where('event_type', 'appointment')->count());
        $this->command->info('   - Daily Work: ' . collect($events)->where('event_type', 'daily_work')->count());
        $this->command->info('   - Meetings: ' . collect($events)->where('event_type', 'meeting')->count());
        $this->command->info('   - Other: ' . collect($events)->where('event_type', 'other')->count());
    }
}
