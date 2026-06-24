<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Général
            [
                'key' => 'site_name',
                'value' => 'TradiPratic',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Nom du site',
                'description' => 'Nom affiché sur le site'
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Médecine traditionnelle africaine',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Slogan',
                'description' => null
            ],
            [
                'key' => 'site_description',
                'value' => 'Plateforme de médecine traditionnelle',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Description',
                'description' => null
            ],

            // Contact
            [
                'key' => 'contact_email',
                'value' => 'contact@tradipratic.bf',
                'type' => 'email',
                'group' => 'contact',
                'label' => 'Email',
                'description' => null
            ],
            [
                'key' => 'contact_phone',
                'value' => '+226 70 12 34 56',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Téléphone',
                'description' => null
            ],
            [
                'key' => 'contact_address',
                'value' => 'Komsilga, Burkina Faso',
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'Adresse',
                'description' => null
            ],

            // SEO
            [
                'key' => 'seo_title',
                'value' => 'TradiPratic - Médecine Traditionnelle',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Titre SEO',
                'description' => null
            ],
            [
                'key' => 'seo_description',
                'value' => 'Découvrez la médecine traditionnelle africaine',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'Description SEO',
                'description' => null
            ],
            [
                'key' => 'seo_keywords',
                'value' => 'médecine, traditionnelle, africaine, burkina',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Mots-clés',
                'description' => 'Séparés par des virgules'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
