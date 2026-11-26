<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // One transaction: SQLite then writes to disk once instead of once per row.
        DB::transaction(function () {
            User::query()->firstOrCreate(
                ['email' => env('ADMIN_EMAIL', 'admin@aurcontracting.com')],
                [
                    'name' => env('ADMIN_NAME', 'AURCO Admin'),
                    'password' => env('ADMIN_PASSWORD', 'password'),
                ],
            );

            $this->call([ContentSeeder::class, TranslationSeeder::class]);
        });
    }
}
