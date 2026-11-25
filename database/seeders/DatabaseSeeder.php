<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@aurcontracting.com')],
            [
                'name' => env('ADMIN_NAME', 'AURCO Admin'),
                'password' => env('ADMIN_PASSWORD', 'password'),
            ],
        );

        $this->call([ContentSeeder::class, TranslationSeeder::class]);
    }
}
