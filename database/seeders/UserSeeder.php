<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        // Créer le super administrateur
        User::create([
            'name' => 'Administrateur',
            'email' => 'athanasesaw@gmail.com',
            'password' => Hash::make('admin@2025'),
            'email_verified_at' => now(),
        ]);

        // Créer un compte de test (optionnel)
        if (app()->environment('local')) {
            User::create([
                'name' => 'Test Gestionnaire',
                'email' => 'test@test.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
