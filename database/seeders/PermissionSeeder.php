<?php
// database/seeders/PermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
            // ========================================
            // GESTION DES UTILISATEURS
            // ========================================
            ['name' => 'users.view', 'description' => 'Voir les utilisateurs'],
            ['name' => 'users.create', 'description' => 'Créer des utilisateurs'],
            ['name' => 'users.edit', 'description' => 'Modifier les utilisateurs'],
            ['name' => 'users.delete', 'description' => 'Supprimer les utilisateurs'],

            // ========================================
            // GESTION DES RÔLES ET PERMISSIONS
            // ========================================
            ['name' => 'roles.view', 'description' => 'Voir les rôles'],
            ['name' => 'roles.create', 'description' => 'Créer des rôles'],
            ['name' => 'roles.edit', 'description' => 'Modifier les rôles'],
            ['name' => 'roles.delete', 'description' => 'Supprimer les rôles'],

            // ========================================
            // SYSTÈME DE DISPONIBILITÉS ET RENDEZ-VOUS
            // ========================================
            ['name' => 'availabilities.view', 'description' => 'Voir les disponibilités'],
            ['name' => 'availabilities.create', 'description' => 'Créer des disponibilités'],
            ['name' => 'availabilities.edit', 'description' => 'Modifier les disponibilités'],
            ['name' => 'availabilities.delete', 'description' => 'Supprimer les disponibilités'],

            ['name' => 'events.view', 'description' => 'Voir les événements'],
            ['name' => 'events.create', 'description' => 'Créer des événements'],
            ['name' => 'events.edit', 'description' => 'Modifier les événements'],
            ['name' => 'events.delete', 'description' => 'Supprimer les événements'],

            ['name' => 'appointments.view', 'description' => 'Voir les rendez-vous'],
            ['name' => 'appointments.create', 'description' => 'Créer des rendez-vous'],
            ['name' => 'appointments.edit', 'description' => 'Modifier les rendez-vous'],
            ['name' => 'appointments.delete', 'description' => 'Supprimer les rendez-vous'],

            // ========================================
            // HÔTELLERIE
            // ========================================
            ['name' => 'hotels.view', 'description' => 'Voir les hôtels'],
            ['name' => 'hotels.create', 'description' => 'Créer des hôtels'],
            ['name' => 'hotels.edit', 'description' => 'Modifier les hôtels'],
            ['name' => 'hotels.delete', 'description' => 'Supprimer les hôtels'],

            ['name' => 'reservations.view', 'description' => 'Voir les réservations'],
            ['name' => 'reservations.create', 'description' => 'Créer des réservations'],
            ['name' => 'reservations.edit', 'description' => 'Modifier les réservations'],
            ['name' => 'reservations.delete', 'description' => 'Supprimer les réservations'],
            ['name' => 'reservations.confirm', 'description' => 'Confirmer les réservations'],
            ['name' => 'reservations.cancel', 'description' => 'Annuler les réservations'],

            // ========================================
            // E-COMMERCE
            // ========================================
            ['name' => 'products.view', 'description' => 'Voir les produits'],
            ['name' => 'products.create', 'description' => 'Créer des produits'],
            ['name' => 'products.edit', 'description' => 'Modifier les produits'],
            ['name' => 'products.delete', 'description' => 'Supprimer les produits'],

            ['name' => 'orders.view', 'description' => 'Voir les commandes'],
            ['name' => 'orders.create', 'description' => 'Créer des commandes'],
            ['name' => 'orders.edit', 'description' => 'Modifier les commandes'],
            ['name' => 'orders.delete', 'description' => 'Supprimer les commandes'],
            ['name' => 'orders.update-status', 'description' => 'Mettre à jour le statut des commandes'],

            // ========================================
            // DONS
            // ========================================
            ['name' => 'donations.view', 'description' => 'Voir les dons'],
            ['name' => 'donations.create', 'description' => 'Créer des dons'],
            ['name' => 'donations.edit', 'description' => 'Modifier les dons'],
            ['name' => 'donations.delete', 'description' => 'Supprimer les dons'],
            ['name' => 'donations.receive', 'description' => 'Marquer les dons comme reçus'],

            // ========================================
            // PAIEMENTS
            // ========================================
            ['name' => 'payments.view', 'description' => 'Voir les paiements'],
            ['name' => 'payments.process', 'description' => 'Traiter les paiements'],

            // ========================================
            // COMMUNICATION
            // ========================================
            ['name' => 'contacts.view', 'description' => 'Voir les messages de contact'],
            ['name' => 'contacts.reply', 'description' => 'Répondre aux messages'],
            ['name' => 'contacts.delete', 'description' => 'Supprimer les messages'],

            ['name' => 'testimonials.view', 'description' => 'Voir les témoignages'],
            ['name' => 'testimonials.approve', 'description' => 'Approuver les témoignages'],
            ['name' => 'testimonials.delete', 'description' => 'Supprimer les témoignages'],

            // ========================================
            // CONTENU
            // ========================================
            ['name' => 'realisations.view', 'description' => 'Voir les réalisations'],
            ['name' => 'realisations.create', 'description' => 'Créer des réalisations'],
            ['name' => 'realisations.edit', 'description' => 'Modifier les réalisations'],
            ['name' => 'realisations.delete', 'description' => 'Supprimer les réalisations'],
            ['name' => 'realisations.publish', 'description' => 'Publier/dépublier les réalisations'],

            ['name' => 'recipes.view', 'description' => 'Voir les recettes'],
            ['name' => 'recipes.create', 'description' => 'Créer des recettes'],
            ['name' => 'recipes.edit', 'description' => 'Modifier les recettes'],
            ['name' => 'recipes.delete', 'description' => 'Supprimer les recettes'],
            ['name' => 'recipes.publish', 'description' => 'Publier/dépublier les recettes'],

            ['name' => 'pub-services.view', 'description' => 'Voir les publicités de services'],
            ['name' => 'pub-services.create', 'description' => 'Créer des publicités de services'],
            ['name' => 'pub-services.edit', 'description' => 'Modifier les publicités de services'],
            ['name' => 'pub-services.delete', 'description' => 'Supprimer les publicités de services'],
            ['name' => 'pub-services.publish', 'description' => 'Publier/dépublier les publicités'],
            ['name' => 'pub-services.approve', 'description' => 'Approuver les publicités'],

            ['name' => 'bibliography.view', 'description' => 'Voir la bibliographie'],
            ['name' => 'bibliography.edit', 'description' => 'Modifier la bibliographie'],

            ['name' => 'media_images.view', 'description' => 'Voir les images'],
            ['name' => 'media_images.create', 'description' => 'Ajouter des images'],
            ['name' => 'media_images.edit', 'description' => 'Modifier les images'],
            ['name' => 'media_images.delete', 'description' => 'Supprimer les images'],

            ['name' => 'media_videos.view', 'description' => 'Voir les vidéos'],
            ['name' => 'media_videos.create', 'description' => 'Ajouter des vidéos'],
            ['name' => 'media_videos.edit', 'description' => 'Modifier les vidéos'],
            ['name' => 'media_videos.delete', 'description' => 'Supprimer les vidéos'],

            // ========================================
            // LIVE STREAMS
            // ========================================
            ['name' => 'livestreams.view', 'description' => 'Voir les lives'],
            ['name' => 'livestreams.create', 'description' => 'Créer des lives'],
            ['name' => 'livestreams.edit', 'description' => 'Modifier les lives'],
            ['name' => 'livestreams.delete', 'description' => 'Supprimer les lives'],
            ['name' => 'livestreams.manage', 'description' => 'Gérer les lives (démarrer/arrêter)'],

            // ========================================
            // LIENS SOCIAUX
            // ========================================
            ['name' => 'social-links.view', 'description' => 'Voir les liens sociaux'],
            ['name' => 'social-links.edit', 'description' => 'Modifier les liens sociaux'],

            // ========================================
            // ADMINISTRATION
            // ========================================
            ['name' => 'settings.view', 'description' => 'Voir les paramètres'],
            ['name' => 'settings.edit', 'description' => 'Modifier les paramètres'],
            ['name' => 'settings.clear-cache', 'description' => 'Vider le cache'],

            ['name' => 'logs.view', 'description' => 'Voir les journaux d\'activité'],
            ['name' => 'logs.clear', 'description' => 'Effacer les journaux d\'activité'],

            // ========================================
            // DASHBOARD
            // ========================================
            ['name' => 'dashboard.view', 'description' => 'Accéder au tableau de bord'],
            ['name' => 'dashboard.stats', 'description' => 'Voir les statistiques'],
        ];

        // ✅ Ajouter UUID + timestamps à chaque permission
        foreach ($permissions as &$permission) {
            $permission['id'] = Str::uuid()->toString();
            $permission['created_at'] = $now;
            $permission['updated_at'] = $now;
        }

        // ✅ Insérer par lots pour éviter les problèmes de taille
        $chunks = array_chunk($permissions, 50);
        foreach ($chunks as $chunk) {
            DB::table('permissions')->insert($chunk);
        }

        $this->command->info('');
        $this->command->info('✅ ' . count($permissions) . ' permissions créées avec succès!');
        $this->command->info('');
        $this->command->info('📊 Détail des permissions :');
        $this->command->info('   👤 Utilisateurs       : 4 permissions');
        $this->command->info('   🛡️  Rôles              : 4 permissions');
        $this->command->info('   📅 Disponibilités     : 4 permissions');
        $this->command->info('   📆 Événements         : 4 permissions');
        $this->command->info('   🤝 Rendez-vous        : 4 permissions');
        $this->command->info('   🏨 Hôtels             : 4 permissions');
        $this->command->info('   📋 Réservations       : 6 permissions');
        $this->command->info('   🛒 Produits           : 4 permissions');
        $this->command->info('   📦 Commandes          : 5 permissions');
        $this->command->info('   🎁 Dons               : 5 permissions');
        $this->command->info('   💳 Paiements          : 2 permissions');
        $this->command->info('   📩 Contacts           : 3 permissions');
        $this->command->info('   ⭐ Témoignages        : 3 permissions');
        $this->command->info('   🎨 Réalisations       : 5 permissions');
        $this->command->info('   🍲 Recettes           : 5 permissions');
        $this->command->info('   📢 Pub Services       : 6 permissions');
        $this->command->info('   📖 Bibliographie      : 2 permissions');
        $this->command->info('   🖼️  Images             : 4 permissions');
        $this->command->info('   🎬 Vidéos             : 4 permissions');
        $this->command->info('   📡 Live Streams       : 5 permissions');
        $this->command->info('   🔗 Liens sociaux      : 2 permissions');
        $this->command->info('   ⚙️  Paramètres         : 3 permissions');
        $this->command->info('   📝 Logs               : 2 permissions');
        $this->command->info('   📊 Dashboard          : 2 permissions');
        $this->command->info('');
        $this->command->info('   📌 Total              : ' . count($permissions) . ' permissions');
    }
}
