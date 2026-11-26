<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/**
 * Loads the Arabic version of the shipped content from data/translations-ar.json.
 * Safe to re-run: Arabic text already edited in the CRM is left untouched.
 *
 *   php artisan db:seed --class=TranslationSeeder
 */
class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/translations-ar.json');

        if (! File::exists($file)) {
            return;
        }

        $existing = Translation::query()->where('locale', 'ar')->pluck('source_hash')->flip();
        $now = now();
        $rows = [];

        foreach (json_decode(File::get($file), true) ?: [] as $source => $text) {
            $source = trim($source);
            $hash = Translation::hash($source);

            if (! $existing->has($hash) && trim((string) $text) !== '') {
                $existing[$hash] = true;
                $rows[] = ['locale' => 'ar', 'source_hash' => $hash, 'source' => $source, 'text' => trim($text), 'created_at' => $now, 'updated_at' => $now];
            }
        }

        // One insert per 100 rows keeps first-start seeding fast (it runs on every new Vercel instance).
        foreach (array_chunk($rows, 100) as $chunk) {
            Translation::query()->insert($chunk);
        }
    }
}
