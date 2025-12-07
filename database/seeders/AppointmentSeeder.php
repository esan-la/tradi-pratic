<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $consultationTypes = [
            'Consultation traditionnelle',
            'Prières et bénédictions',
            'Soins naturels',
            'Consultation spirituelle',
        ];

        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        $appointments = [
            // Rendez-vous passés (completed)
            [
                'name' => 'Amadou Traoré',
                'phone' => '+226 70 12 34 56',
                'email' => 'amadou.traore@email.com',
                'consultation_type' => 'Consultation traditionnelle',
                'preferred_date' => Carbon::now()->subDays(15),
                'preferred_time' => '09:00',
                'message' => 'Je souhaite une consultation pour des problèmes de santé persistants.',
                'status' => 'completed',
                'admin_notes' => 'Consultation effectuée avec succès. Traitement prescrit.',
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'name' => 'Fatoumata Ouédraogo',
                'phone' => '+226 75 23 45 67',
                'email' => 'fatoumata.ouedraogo@email.com',
                'consultation_type' => 'Soins naturels',
                'preferred_date' => Carbon::now()->subDays(10),
                'preferred_time' => '14:30',
                'message' => 'Besoin de soins naturels pour des douleurs articulaires.',
                'status' => 'completed',
                'admin_notes' => 'Traitement à base de plantes médicinales prescrit. Suivi dans 2 semaines.',
                'created_at' => Carbon::now()->subDays(12),
            ],
            [
                'name' => 'Ibrahim Sawadogo',
                'phone' => '+226 71 34 56 78',
                'email' => null,
                'consultation_type' => 'Prières et bénédictions',
                'preferred_date' => Carbon::now()->subDays(7),
                'preferred_time' => '10:00',
                'message' => 'Demande de bénédiction pour un nouveau projet.',
                'status' => 'completed',
                'admin_notes' => 'Séance de prières effectuée.',
                'created_at' => Carbon::now()->subDays(9),
            ],

            // Rendez-vous confirmés (à venir)
            [
                'name' => 'Mariam Compaoré',
                'phone' => '+226 76 45 67 89',
                'email' => 'mariam.compaore@email.com',
                'consultation_type' => 'Consultation spirituelle',
                'preferred_date' => Carbon::now()->addDays(2),
                'preferred_time' => '09:30',
                'message' => 'Consultation spirituelle pour orientation de vie.',
                'status' => 'confirmed',
                'admin_notes' => 'Client régulier. Préparer les outils divinatoires.',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'name' => 'Souleymane Kaboré',
                'phone' => '+226 72 56 78 90',
                'email' => 'souleymane.kabore@email.com',
                'consultation_type' => 'Consultation traditionnelle',
                'preferred_date' => Carbon::now()->addDays(3),
                'preferred_time' => '15:00',
                'message' => 'Première consultation. Recommandé par un ami.',
                'status' => 'confirmed',
                'admin_notes' => 'Nouveau patient. Prévoir entretien initial complet.',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'name' => 'Aïssata Diallo',
                'phone' => '+226 77 67 89 01',
                'email' => 'aissata.diallo@email.com',
                'consultation_type' => 'Soins naturels',
                'preferred_date' => Carbon::now()->addDays(5),
                'preferred_time' => '11:00',
                'message' => 'Suivi de traitement pour problèmes digestifs.',
                'status' => 'confirmed',
                'admin_notes' => 'Deuxième consultation. Vérifier évolution du traitement.',
                'created_at' => Carbon::now()->subDays(2),
            ],

            // Rendez-vous en attente
            [
                'name' => 'Moussa Ouattara',
                'phone' => '+226 73 78 90 12',
                'email' => null,
                'consultation_type' => 'Prières et bénédictions',
                'preferred_date' => Carbon::now()->addDays(7),
                'preferred_time' => '08:00',
                'message' => 'Bénédiction pour mariage.',
                'status' => 'pending',
                'admin_notes' => null,
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'name' => 'Rasmata Zongo',
                'phone' => '+226 74 89 01 23',
                'email' => 'rasmata.zongo@email.com',
                'consultation_type' => 'Consultation spirituelle',
                'preferred_date' => Carbon::now()->addDays(8),
                'preferred_time' => '16:00',
                'message' => 'Besoin de guidance spirituelle pour des décisions importantes.',
                'status' => 'pending',
                'admin_notes' => null,
                'created_at' => Carbon::now()->subHours(12),
            ],
            [
                'name' => 'Boukary Yaméogo',
                'phone' => '+226 75 90 12 34',
                'email' => 'boukary.yameogo@email.com',
                'consultation_type' => 'Consultation traditionnelle',
                'preferred_date' => Carbon::now()->addDays(10),
                'preferred_time' => '13:30',
                'message' => 'Consultation pour problèmes familiaux.',
                'status' => 'pending',
                'admin_notes' => null,
                'created_at' => Carbon::now()->subHours(6),
            ],

            // Rendez-vous très prochains (aujourd'hui et demain)
            [
                'name' => 'Adama Sanogo',
                'phone' => '+226 70 23 45 67',
                'email' => 'adama.sanogo@email.com',
                'consultation_type' => 'Consultation traditionnelle',
                'preferred_date' => Carbon::now(),
                'preferred_time' => '17:00',
                'message' => 'Rendez-vous urgent.',
                'status' => 'confirmed',
                'admin_notes' => 'Rappeler le client 1h avant.',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'name' => 'Zénabo Bassolé',
                'phone' => '+226 71 34 56 78',
                'email' => 'zenabo.bassole@email.com',
                'consultation_type' => 'Prières et bénédictions',
                'preferred_date' => Carbon::now()->addDay(),
                'preferred_time' => '09:00',
                'message' => 'Bénédiction pour nouvelle boutique.',
                'status' => 'confirmed',
                'admin_notes' => 'Prévoir déplacement sur site si nécessaire.',
                'created_at' => Carbon::now()->subDays(3),
            ],
        ];

        foreach ($appointments as $appointment) {
            Appointment::create($appointment);
        }

        $this->command->info('✅ ' . count($appointments) . ' rendez-vous créés avec succès!');
    }
}
