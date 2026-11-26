<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Title, description and share image for each public page, in the visitor's language.
 * Rendered into the HTML by app.blade.php so search engines and link previews
 * (WhatsApp, LinkedIn, X) see them without running JavaScript.
 */
class PageMeta
{
    public const PAGES = ['home' => '/', 'about' => '/about', 'services' => '/services', 'projects' => '/projects',
        'credentials' => '/credentials', 'gallery' => '/gallery', 'contact' => '/contact'];

    public static function for(string $page): array
    {
        $c = SiteContent::localized();
        $locale = app()->getLocale();
        $site = $c['company']['name'] ?? 'AURCO Contracting';

        [$title, $description, $image] = match ($page) {
            'about' => [$c['aboutPage']['hero']['title'], $c['aboutPage']['hero']['text'], $c['aboutPage']['intro']['image'] ?? null],
            'services' => [$c['servicesPage']['hero']['title'], $c['servicesPage']['hero']['text'], $c['servicesPage']['items'][0]['image'] ?? null],
            'projects' => [$c['projectsPage']['hero']['title'], $c['projectsPage']['hero']['text'], $c['projectsPage']['items'][0]['image'] ?? null],
            'credentials' => [$c['credentialsPage']['hero']['title'], $c['credentialsPage']['hero']['text'], $c['credentialsPage']['iso']['images'][0] ?? null],
            'gallery' => [$c['galleryPage']['hero']['title'], $c['galleryPage']['hero']['text'], $c['galleryPage']['items'][0]['src'] ?? null],
            'contact' => [$c['contactPage']['hero']['title'], $c['contactPage']['hero']['text'], $c['about']['image'] ?? null],
            default => [$c['hero']['title'], $c['hero']['text'], $c['projects']['items'][0]['image'] ?? null],
        };

        $base = static::url(static::PAGES[$page] ?? '/');
        $localized = fn (string $lang) => $base.'?lang='.$lang;

        return [
            'title' => $page === 'home' ? "$site | $title" : "$title | $site",
            'description' => Str::limit(trim((string) $description), 160),
            'image' => $image,
            'site' => $site,
            'locale' => $locale === 'ar' ? 'ar_SA' : 'en_US',
            'canonical' => $locale === 'ar' ? $localized('ar') : $base,
            'alternates' => ['en' => $localized('en'), 'ar' => $localized('ar'), 'x-default' => $base],
            'indexable' => static::indexable(),
        ];
    }

    /** Absolute address of a page; the home page keeps its trailing slash ("https://site/"). */
    public static function url(string $path): string
    {
        return rtrim(url('/'), '/').$path;
    }

    /** Staging copies (such as the Vercel demo) ask search engines not to index them. */
    public static function indexable(): bool
    {
        return filter_var(env('SEARCH_INDEXING', true), FILTER_VALIDATE_BOOLEAN);
    }
}
