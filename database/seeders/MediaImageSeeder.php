<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MediaImage;
use App\Models\User;

class MediaImageSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Aucun utilisateur trouvé.');
            return;
        }

        foreach ($users as $user) {
            for ($i = 1; $i <= 5; $i++) {
                MediaImage::create([
                    'user_id' => $user->id,
                    'image_path' => 'media/images/sample_' . rand(1, 10) . '.jpg',
                    'is_published' => rand(0, 1),
                ]);
            }
        }
    }
}
