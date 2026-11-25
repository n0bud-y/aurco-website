<?php

namespace App\Support;

use App\Models\Translation;

/**
 * Swaps the English strings in the site payload for their saved translations.
 * Anything without a translation stays in English, so the site never shows gaps.
 */
class Translator
{
    public const LOCALES = ['en' => 'English', 'ar' => 'العربية'];

    /** Keys whose values are links, ids or markers, never visible copy. */
    private const SKIP_KEYS = [
        'image', 'src', 'avatar', 'logo', 'href', 'to', 'phoneHref', 'supportPhoneHref', 'email',
        'whatsapp', 'mapQuery', 'id', 'key', 'network', 'visionImage', 'agentImage', 'icon', 'type',
        'phone', 'supportPhone', 'fax', 'count', 'number', 'manhours', 'fai', 'mtc', 'lti',
        'nearMiss', 'environmental', 'fatalities', 'csrImage', 'governmentImage', 'names',
    ];

    public static function isRtl(string $locale): bool
    {
        return $locale === 'ar';
    }

    public static function translate(array $payload, string $locale): array
    {
        $payload = static::plain($payload);

        if ($locale === 'en') {
            return $payload;
        }

        $map = Translation::map($locale);

        return static::walk($payload, function (string $text) use ($map) {
            return $map[Translation::hash($text)] ?? $text;
        });
    }

    /** Every distinct translatable string in the payload, in page order. */
    public static function strings(array $payload): array
    {
        $found = [];
        static::walk(static::plain($payload), function (string $text) use (&$found) {
            $found[trim($text)] = true;

            return $text;
        });

        return array_keys($found);
    }

    private static function walk(array $data, callable $fn): array
    {
        foreach ($data as $key => $value) {
            if (is_string($key) && in_array($key, self::SKIP_KEYS, true)) {
                continue;
            }

            if (is_array($value)) {
                $data[$key] = static::walk($value, $fn);
            } elseif (is_string($value) && static::translatable($value)) {
                $data[$key] = $fn($value);
            }
        }

        return $data;
    }

    private static function translatable(string $text): bool
    {
        $text = trim($text);

        return $text !== ''
            && preg_match('/\p{L}/u', $text)
            && ! preg_match('#^(https?://|/|mailto:|tel:|\#)#i', $text);
    }

    /** Turns collections and other objects into plain arrays. */
    private static function plain(array $payload): array
    {
        return json_decode(json_encode($payload), true);
    }
}
