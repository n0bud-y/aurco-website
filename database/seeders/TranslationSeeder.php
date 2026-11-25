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

        foreach (json_decode(File::get($file), true) ?: [] as $source => $text) {
            if (! $existing->has(Translation::hash($source))) {
                Translation::put('ar', $source, $text);
            }
        }
    }
}
