<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SocialLink;
use Illuminate\Support\Facades\DB;

class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        // Supprimer les anciennes données
        DB::table('social_links')->truncate();

        $links = [
            [
                'platform' => 'facebook',
                'url' => 'https://facebook.com/tradipratic',
                'icon' => 'fab fa-facebook-f',
            ],
            [
                'platform' => 'instagram',
                'url' => 'https://instagram.com/tradipratic',
                'icon' => 'fab fa-instagram',
            ],
            [
                'platform' => 'whatsapp',
                'url' => 'https://wa.me/22670123456',
                'icon' => 'fab fa-whatsapp',
            ],
            [
                'platform' => 'youtube',
                'url' => 'https://youtube.com/@tradipratic',
                'icon' => 'fab fa-youtube',
            ],
            [
                'platform' => 'linkedin',
                'url' => 'https://linkedin.com/company/tradipratic',
                'icon' => 'fab fa-linkedin-in',
            ],
            [
                'platform' => 'twitter',
                'url' => 'https://twitter.com/tradipratic',
                'icon' => 'fab fa-twitter',
            ],
        ];

        foreach ($links as $link) {
            SocialLink::create($link);
        }

        $this->command->info('✅ 6 liens sociaux créés avec succès.');
    }
}
