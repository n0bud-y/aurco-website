<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One translated string. The English text is the key, so edits to English show up as "missing". */
class Translation extends Model
{
    protected $fillable = ['locale', 'source_hash', 'source', 'text'];

    public static function hash(string $source): string
    {
        return sha1(trim($source));
    }

    /** @return array<string, string> source hash => translated text */
    public static function map(string $locale): array
    {
        return static::query()->where('locale', $locale)->pluck('text', 'source_hash')->all();
    }

    public static function put(string $locale, string $source, ?string $text): void
    {
        $source = trim($source);
        $text = trim((string) $text);

        if ($text === '') {
            static::query()->where('locale', $locale)->where('source_hash', static::hash($source))->delete();

            return;
        }

        static::query()->updateOrCreate(
            ['locale' => $locale, 'source_hash' => static::hash($source)],
            ['source' => $source, 'text' => $text],
        );
    }
}
