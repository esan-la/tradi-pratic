<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MediaVideo;
use App\Models\User;

class MediaVideoSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Aucun utilisateur trouvé.');
            return;
        }

        $sampleVideos = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'https://www.youtube.com/watch?v=3tmd-ClpJxA',
            'https://vimeo.com/76979871',
        ];

        foreach ($users as $user) {
            for ($i = 1; $i <= 3; $i++) {
                MediaVideo::create([
                    'user_id' => $user->id,
                    'video_url' => $sampleVideos[array_rand($sampleVideos)],
                    'is_published' => rand(0, 1),
                ]);
            }
        }
    }
}
