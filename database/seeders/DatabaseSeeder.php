<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Picture;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Création de 5 user, pour chaque user on cré entre 1 et 3 images
        User::factory(5)->create()->each(function ($user) {
            Picture::factory(rand(1, 3))->create([
                // Les foreignId ne sont pas créé dans la factory donc on les cré ici
                'user_id' => $user->id,
            ]);
        });
    }
}
