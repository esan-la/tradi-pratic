<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $permissions = [
            // Gestion des utilisateurs
            ['name' => 'users.view', 'description' => 'Voir les utilisateurs'],
            ['name' => 'users.create', 'description' => 'Créer des utilisateurs'],
            ['name' => 'users.edit', 'description' => 'Modifier les utilisateurs'],
            ['name' => 'users.delete', 'description' => 'Supprimer les utilisateurs'],

            // Gestion des rôles et permissions
            ['name' => 'roles.view', 'description' => 'Voir les rôles'],
            ['name' => 'roles.create', 'description' => 'Créer des rôles'],
            ['name' => 'roles.edit', 'description' => 'Modifier les rôles'],
            ['name' => 'roles.delete', 'description' => 'Supprimer les rôles'],

            // ========================================
            // SYSTÈME DE DISPONIBILITÉS ET RENDEZ-VOUS
            // ========================================

            // Gestion des disponibilités (Availability Periods)
            ['name' => 'availabilities.view', 'description' => 'Voir les disponibilités'],
            ['name' => 'availabilities.create', 'description' => 'Créer des disponibilités'],
            ['name' => 'availabilities.edit', 'description' => 'Modifier les disponibilités'],
            ['name' => 'availabilities.delete', 'description' => 'Supprimer les disponibilités'],

            // Gestion des événements (Events)
            ['name' => 'events.view', 'description' => 'Voir les événements'],
            ['name' => 'events.create', 'description' => 'Créer des événements'],
            ['name' => 'events.edit', 'description' => 'Modifier les événements'],
            ['name' => 'events.delete', 'description' => 'Supprimer les événements'],

            // Gestion des rendez-vous (Appointments)
            ['name' => 'appointments.view', 'description' => 'Voir les rendez-vous'],
            ['name' => 'appointments.create', 'description' => 'Créer des rendez-vous'],
            ['name' => 'appointments.edit', 'description' => 'Modifier les rendez-vous'],
            ['name' => 'appointments.delete', 'description' => 'Supprimer les rendez-vous'],

            // ========================================
            // HÔTELLERIE
            // ========================================

            // Gestion des hôtels
            ['name' => 'hotels.view', 'description' => 'Voir les hôtels'],
            ['name' => 'hotels.create', 'description' => 'Créer des hôtels'],
            ['name' => 'hotels.edit', 'description' => 'Modifier les hôtels'],
            ['name' => 'hotels.delete', 'description' => 'Supprimer les hôtels'],

            // Gestion des réservations
            ['name' => 'reservations.view', 'description' => 'Voir les réservations'],
            ['name' => 'reservations.create', 'description' => 'Créer des réservations'],
            ['name' => 'reservations.edit', 'description' => 'Modifier les réservations'],
            ['name' => 'reservations.delete', 'description' => 'Supprimer les réservations'],
            ['name' => 'reservations.confirm', 'description' => 'Confirmer les réservations'],
            ['name' => 'reservations.cancel', 'description' => 'Annuler les réservations'],

            // ========================================
            // E-COMMERCE
            // ========================================

            // Gestion des produits
            ['name' => 'products.view', 'description' => 'Voir les produits'],
            ['name' => 'products.create', 'description' => 'Créer des produits'],
            ['name' => 'products.edit', 'description' => 'Modifier les produits'],
            ['name' => 'products.delete', 'description' => 'Supprimer les produits'],

            // Gestion des commandes
            ['name' => 'orders.view', 'description' => 'Voir les commandes'],
            ['name' => 'orders.create', 'description' => 'Créer des commandes'],
            ['name' => 'orders.edit', 'description' => 'Modifier les commandes'],
            ['name' => 'orders.delete', 'description' => 'Supprimer les commandes'],
            ['name' => 'orders.update-status', 'description' => 'Mettre à jour le statut des commandes'],

            // ========================================
            // DONS
            // ========================================

            // Gestion des dons
            ['name' => 'donations.view', 'description' => 'Voir les dons'],
            ['name' => 'donations.create', 'description' => 'Créer des dons'],
            ['name' => 'donations.edit', 'description' => 'Modifier les dons'],
            ['name' => 'donations.delete', 'description' => 'Supprimer les dons'],
            ['name' => 'donations.receive', 'description' => 'Marquer les dons comme reçus'],

            // ========================================
            // PAIEMENTS
            // ========================================

            // Gestion des paiements
            ['name' => 'payments.view', 'description' => 'Voir les paiements'],
            ['name' => 'payments.process', 'description' => 'Traiter les paiements'],

            // ========================================
            // COMMUNICATION
            // ========================================

            // Gestion des contacts
            ['name' => 'contacts.view', 'description' => 'Voir les messages de contact'],
            ['name' => 'contacts.reply', 'description' => 'Répondre aux messages'],
            ['name' => 'contacts.delete', 'description' => 'Supprimer les messages'],

            // Gestion des témoignages
            ['name' => 'testimonials.view', 'description' => 'Voir les témoignages'],
            ['name' => 'testimonials.approve', 'description' => 'Approuver les témoignages'],
            ['name' => 'testimonials.delete', 'description' => 'Supprimer les témoignages'],

            // ========================================
            // CONTENU
            // ========================================

            // Gestion des réalisations
            ['name' => 'realisations.view', 'description' => 'Voir les réalisations'],
            ['name' => 'realisations.create', 'description' => 'Créer des réalisations'],
            ['name' => 'realisations.edit', 'description' => 'Modifier les réalisations'],
            ['name' => 'realisations.delete', 'description' => 'Supprimer les réalisations'],
            ['name' => 'realisations.publish', 'description' => 'Publier/dépublier les réalisations'],

            // Gestion des recettes
            ['name' => 'recipes.view', 'description' => 'Voir les recettes'],
            ['name' => 'recipes.create', 'description' => 'Créer des recettes'],
            ['name' => 'recipes.edit', 'description' => 'Modifier les recettes'],
            ['name' => 'recipes.delete', 'description' => 'Supprimer les recettes'],
            ['name' => 'recipes.publish', 'description' => 'Publier/dépublier les recettes'],

            // Gestion de la publicité de services
            ['name' => 'pub-services.view', 'description' => 'Voir les publicités de services'],
            ['name' => 'pub-services.create', 'description' => 'Créer des publicités de services'],
            ['name' => 'pub-services.edit', 'description' => 'Modifier les publicités de services'],
            ['name' => 'pub-services.delete', 'description' => 'Supprimer les publicités de services'],
            ['name' => 'pub-services.publish', 'description' => 'Publier/dépublier les publicités'],
            ['name' => 'pub-services.approve', 'description' => 'Approuver les publicités'],

            // Gestion de la bibliographie
            ['name' => 'bibliography.view', 'description' => 'Voir la bibliographie'],
            ['name' => 'bibliography.edit', 'description' => 'Modifier la bibliographie'],

            // Médias - Images (8 permissions)
            ['name' => 'media_images.view', 'description' => 'Voir les images'],
            ['name' => 'media_images.create', 'description' => 'Ajouter des images'],
            ['name' => 'media_images.edit', 'description' => 'Modifier les images'],
            ['name' => 'media_images.delete', 'description' => 'Supprimer les images'],

            // Médias - Vidéos (4 permissions)
            ['name' => 'media_videos.view', 'description' => 'Voir les vidéos'],
            ['name' => 'media_videos.create', 'description' => 'Ajouter des vidéos'],
            ['name' => 'media_videos.edit', 'description' => 'Modifier les vidéos'],
            ['name' => 'media_videos.delete', 'description' => 'Supprimer les vidéos'],


            // ========================================
            // ADMINISTRATION
            // ========================================

            // Gestion des paramètres
            ['name' => 'settings.view', 'description' => 'Voir les paramètres'],
            ['name' => 'settings.edit', 'description' => 'Modifier les paramètres'],
            ['name' => 'settings.clear-cache', 'description' => 'Vider le cache'],

            // Gestion des logs
            ['name' => 'logs.view', 'description' => 'Voir les journaux d\'activité'],
            ['name' => 'logs.clear', 'description' => 'Effacer les journaux d\'activité'],
        ];

        foreach ($permissions as &$permission) {
            $permission['created_at'] = $now;
            $permission['updated_at'] = $now;
        }

        DB::table('permissions')->insert($permissions);

        $this->command->info('✅ ' . count($permissions) . ' permissions créées avec succès!');
        $this->command->info('📊 Nouvelles permissions système rendez-vous: 12');
        $this->command->info('   - Availabilities: 4 permissions');
        $this->command->info('   - Events: 4 permissions');
        $this->command->info('   - Appointments: 4 permissions');
    }
}
