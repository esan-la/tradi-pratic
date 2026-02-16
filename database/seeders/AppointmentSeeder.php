<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les événements de type "appointment"
        $events = Event::where('event_type', 'appointment')->orderBy('start_datetime')->get();

        if ($events->isEmpty()) {
            $this->command->warn('⚠️ Aucun événement de type appointment trouvé. Exécuter EventSeeder d\'abord.');
            return;
        }

        $appointmentsData = [
            // Rendez-vous passés (completed)
            [
                'name' => 'Amadou Traoré',
                'phone' => '+226 70 12 34 56',
                'provenance' => 'Mali',
                'email' => 'amadou.traore@email.com',
                'doctype' => 'Passeport',
                'docnumber' => 'ML123456',
                'imagedoc' => null,
                'consultation_type' => 'traditional',
                'autre_consultation' => null,
                'message' => 'Je souhaite une consultation pour des problèmes de santé persistants.',
                'status' => 'completed',
                'admin_notes' => 'Consultation effectuée avec succès. Traitement prescrit pour 2 semaines. Rendez-vous de suivi recommandé.',
            ],
            [
                'name' => 'Fatoumata Ouédraogo',
                'phone' => '+226 75 23 45 67',
                'provenance' => 'Togo',
                'email' => 'fatoumata.ouedraogo@email.com',
                'doctype' => 'CNI',
                'docnumber' => 'TG789012',
                'imagedoc' => null,
                'consultation_type' => 'natural_care',
                'autre_consultation' => null,
                'message' => 'Besoin de soins naturels pour des douleurs articulaires.',
                'status' => 'completed',
                'admin_notes' => 'Traitement à base de plantes médicinales prescrit. Suivi dans 2 semaines. Évolution positive notée.',
            ],
            [
                'name' => 'Ibrahim Sawadogo',
                'phone' => '+226 71 34 56 78',
                'provenance' => 'New York',
                'email' => null,
                'doctype' => null,
                'docnumber' => null,
                'imagedoc' => null,
                'consultation_type' => 'prayer',
                'autre_consultation' => null,
                'message' => 'Demande de bénédiction pour un nouveau projet.',
                'status' => 'completed',
                'admin_notes' => 'Séance de prières effectuée. Amulettes de protection remises.',
            ],

            // Rendez-vous confirmés (à venir)
            [
                'name' => 'Mariam Compaoré',
                'phone' => '+226 76 45 67 89',
                'provenance' => 'Canada',
                'email' => 'mariam.compaore@email.com',
                'doctype' => 'Passeport',
                'docnumber' => 'CA456789',
                'imagedoc' => null,
                'consultation_type' => 'Consultation_spirituelle',
                'autre_consultation' => null,
                'message' => 'Consultation spirituelle pour orientation de vie. Je traverse une période difficile.',
                'status' => 'confirmed',
                'admin_notes' => 'Client régulier. Préparer les outils divinatoires. Prévoir session d\'1h minimum.',
            ],
            [
                'name' => 'Souleymane Kaboré',
                'phone' => '+226 72 56 78 90',
                'provenance' => 'Chine',
                'email' => 'souleymane.kabore@email.com',
                'doctype' => null,
                'docnumber' => null,
                'imagedoc' => null,
                'consultation_type' => 'traditional',
                'autre_consultation' => null,
                'message' => 'Première consultation. Recommandé par un ami. Problèmes de sommeil.',
                'status' => 'confirmed',
                'admin_notes' => 'Nouveau patient. Prévoir entretien initial complet. Anamnèse détaillée.',
            ],
            [
                'name' => 'Aïssata Diallo',
                'phone' => '+226 77 67 89 01',
                'provenance' => 'Taiwan',
                'email' => 'aissata.diallo@email.com',
                'doctype' => 'CNI',
                'docnumber' => 'BF345678',
                'imagedoc' => null,
                'consultation_type' => 'natural_care',
                'autre_consultation' => null,
                'message' => 'Suivi de traitement pour problèmes digestifs. Deuxième consultation.',
                'status' => 'confirmed',
                'admin_notes' => 'Deuxième consultation. Vérifier évolution du traitement. Ajuster dosage si nécessaire.',
            ],

            // Rendez-vous en attente
            [
                'name' => 'Moussa Ouattara',
                'phone' => '+226 73 78 90 12',
                'provenance' => 'France',
                'email' => null,
                'doctype' => null,
                'docnumber' => null,
                'imagedoc' => null,
                'consultation_type' => 'prayer',
                'autre_consultation' => null,
                'message' => 'Bénédiction pour mariage prévu dans 2 mois.',
                'status' => 'pending',
                'admin_notes' => null,
            ],
            [
                'name' => 'Rasmata Zongo',
                'phone' => '+226 74 89 01 23',
                'provenance' => 'Burkina Faso',
                'email' => 'rasmata.zongo@email.com',
                'doctype' => 'CNI',
                'docnumber' => 'BF567890',
                'imagedoc' => null,
                'consultation_type' => 'Consultation_spirituelle',
                'autre_consultation' => null,
                'message' => 'Besoin de guidance spirituelle pour des décisions importantes concernant ma carrière.',
                'status' => 'pending',
                'admin_notes' => null,
            ],
            [
                'name' => 'Boukary Yaméogo',
                'phone' => '+226 75 90 12 34',
                'provenance' => 'USA',
                'email' => 'boukary.yameogo@email.com',
                'doctype' => 'Passeport',
                'docnumber' => 'US123456',
                'imagedoc' => null,
                'consultation_type' => 'traditional',
                'autre_consultation' => null,
                'message' => 'Consultation pour problèmes familiaux. Situation complexe.',
                'status' => 'pending',
                'admin_notes' => null,
            ],

            // Rendez-vous très prochains (aujourd'hui et demain)
            [
                'name' => 'Adama Sanogo',
                'phone' => '+226 70 23 45 67',
                'provenance' => 'Benin',
                'email' => 'adama.sanogo@email.com',
                'doctype' => 'CNI',
                'docnumber' => 'BJ234567',
                'imagedoc' => null,
                'consultation_type' => 'traditional',
                'autre_consultation' => null,
                'message' => 'Rendez-vous urgent. Besoin d\'aide rapidement.',
                'status' => 'confirmed',
                'admin_notes' => 'Rappeler le client 1h avant. Cas urgent signalé.',
            ],
            [
                'name' => 'Zénabo Bassolé',
                'phone' => '+226 71 34 56 78',
                'provenance' => 'Marocco',
                'email' => 'zenabo.bassole@email.com',
                'doctype' => 'Passeport',
                'docnumber' => 'MA789012',
                'imagedoc' => null,
                'consultation_type' => 'prayer',
                'autre_consultation' => null,
                'message' => 'Bénédiction pour nouvelle boutique. Ouverture prévue la semaine prochaine.',
                'status' => 'confirmed',
                'admin_notes' => 'Prévoir déplacement sur site si nécessaire. Client souhaite bénédiction des lieux.',
            ],

            // Rendez-vous avec type "Autres"
            [
                'name' => 'Aminata Traoré',
                'phone' => '+226 72 45 67 89',
                'provenance' => 'Côte d\'Ivoire',
                'email' => 'aminata.traore@email.com',
                'doctype' => 'CNI',
                'docnumber' => 'CI890123',
                'imagedoc' => null,
                'consultation_type' => 'Autres',
                'autre_consultation' => 'Rituel de protection pour voyage',
                'message' => 'Je pars en voyage dans 3 semaines et souhaite une protection.',
                'status' => 'pending',
                'admin_notes' => null,
            ],

            // Rendez-vous annulé
            [
                'name' => 'Mamadou Konaté',
                'phone' => '+226 73 56 78 90',
                'provenance' => 'Guinée',
                'email' => null,
                'doctype' => null,
                'docnumber' => null,
                'imagedoc' => null,
                'consultation_type' => 'traditional',
                'autre_consultation' => null,
                'message' => 'Consultation pour problèmes de santé.',
                'status' => 'cancelled',
                'admin_notes' => 'Client a annulé 24h avant. Problème personnel mentionné.',
            ],
        ];

        $created = 0;
        foreach ($appointmentsData as $index => $data) {
            if ($index < $events->count()) {
                $event = $events[$index];
                $data['event_id'] = $event->id;
                Appointment::create($data);
                $created++;
            }
        }

        $this->command->info('✅ ' . $created . ' rendez-vous créés avec succès!');
        $this->command->info('📊 Répartition par statut:');
        $this->command->info('   - Pending: ' . collect($appointmentsData)->where('status', 'pending')->count());
        $this->command->info('   - Confirmed: ' . collect($appointmentsData)->where('status', 'confirmed')->count());
        $this->command->info('   - Completed: ' . collect($appointmentsData)->where('status', 'completed')->count());
        $this->command->info('   - Cancelled: ' . collect($appointmentsData)->where('status', 'cancelled')->count());
    }
}
