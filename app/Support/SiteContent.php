<?php

namespace App\Support;

use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\TrackRecord;
use Illuminate\Support\Str;

/** Everything the React front end renders, assembled from the CRM. */
class SiteContent
{
    /** @var array<string, array> per-request cache of the translated payload */
    private static array $localized = [];

    /** The payload in the current language, built once per request (page props and meta tags share it). */
    public static function localized(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return static::$localized[$locale] ??= Translator::translate(static::payload(), $locale);
    }

    public static function payload(): array
    {
        $company = Setting::group('company');
        $hero = Setting::group('hero');
        $about = Setting::group('about');
        $services = Setting::group('services_section');
        $expertise = Setting::group('expertise');
        $projectsSection = Setting::group('projects_section');
        $cta = Setting::group('cta_banner');
        $testimonials = Setting::group('testimonials_section');
        $faqSection = Setting::group('faq_section');
        $blogSection = Setting::group('blog_section');
        $contact = Setting::group('contact_section');
        $footer = Setting::group('footer');
        $pages = Setting::group('pages');
        $stats = Setting::group('stats');
        $clients = Setting::group('clients');
        $aboutPage = Setting::group('about_page');
        $servicesPage = Setting::group('services_page');
        $credentials = Setting::group('credentials_page');

        $projects = Project::published()->ordered()->get();
        $featured = $projects->where('is_featured', true)->take(4);
        if ($featured->isEmpty()) {
            $featured = $projects->take(4);
        }

        $gallery = GalleryImage::published()->ordered()->get();

        return [
            'company' => [
                'name' => $company['name'] ?? 'AURCO Contracting',
                'tagline' => $company['tagline'] ?? null,
                'fax' => $company['fax'] ?? null,
                'phone' => $company['phone'] ?? null,
                'phoneHref' => $company['phone_href'] ?? null,
                'email' => $company['email'] ?? null,
                'whatsapp' => $company['whatsapp'] ?? null,
                'address' => $company['address'] ?? null,
                'legalName' => $company['legal_name'] ?? null,
                'logo' => Media::url($company['logo'] ?? null),
            ],

            'hero' => [
                'eyebrow' => $hero['eyebrow'] ?? null,
                'title' => $hero['title'] ?? null,
                'text' => $hero['text'] ?? null,
                'image' => Media::url($hero['image'] ?? null),
            ],

            'about' => [
                'eyebrow' => $about['eyebrow'] ?? null,
                'title' => $about['title'] ?? null,
                'text' => $about['text'] ?? null,
                'features' => $about['features'] ?? [],
                'supportLabel' => $about['support_label'] ?? null,
                'supportPhone' => $about['support_phone'] ?? null,
                'supportPhoneHref' => $about['support_phone_href'] ?? null,
                'image' => Media::url($about['image'] ?? null),
                'imageAlt' => 'AURCO engineers on a construction site',
            ],

            'services' => [
                'eyebrow' => $services['eyebrow'] ?? null,
                'title' => $services['title'] ?? null,
                'text' => $services['text'] ?? null,
                'cta' => $services['cta_label'] ?? null,
                'items' => Service::published()->ordered()->get()
                    ->map(fn (Service $service) => [
                        'title' => $service->title,
                        'image' => $service->image_url,
                        'to' => '/services#'.Str::slug($service->title),
                    ])->values(),
            ],

            'whyChooseUs' => [
                'eyebrow' => $expertise['eyebrow'] ?? null,
                'title' => $expertise['title'] ?? null,
                'text' => $expertise['text'] ?? null,
                'tiles' => static::expertiseTiles($expertise),
            ],

            'projects' => [
                'eyebrow' => $projectsSection['eyebrow'] ?? null,
                'title' => $projectsSection['title'] ?? null,
                'text' => $projectsSection['text'] ?? null,
                'cta' => $projectsSection['cta_label'] ?? null,
                'items' => $featured->map(fn (Project $project) => [
                    'title' => $project->title,
                    'image' => $project->image_url,
                    'meta' => $project->location,
                    'tag' => $project->client,
                ])->values(),
            ],

            'ctaBanner' => [
                'title' => $cta['title'] ?? null,
                'text' => $cta['text'] ?? null,
                'cta' => $cta['cta_label'] ?? null,
                'image' => Media::url($cta['image'] ?? null),
            ],

            'testimonials' => [
                'eyebrow' => $testimonials['eyebrow'] ?? null,
                'title' => $testimonials['title'] ?? null,
                'text' => $testimonials['text'] ?? null,
                'items' => Testimonial::published()->ordered()->get()
                    ->map(fn (Testimonial $item) => [
                        'name' => $item->name,
                        'role' => $item->role,
                        'quote' => $item->quote,
                        'rating' => $item->rating,
                        'avatar' => $item->avatar_url,
                    ])->values(),
            ],

            'faq' => [
                'eyebrow' => $faqSection['eyebrow'] ?? null,
                'title' => $faqSection['title'] ?? null,
                'text' => $faqSection['text'] ?? null,
                'images' => collect([1, 2, 3, 4])
                    ->map(fn (int $i) => Media::url($faqSection["image$i"] ?? null))
                    ->filter()->values(),
                'items' => Faq::published()->ordered()->get()
                    ->map(fn (Faq $faq) => ['q' => $faq->question, 'a' => $faq->answer])->values(),
            ],

            'blog' => [
                'eyebrow' => $blogSection['eyebrow'] ?? null,
                'title' => $blogSection['title'] ?? null,
                'text' => $blogSection['text'] ?? null,
                'posts' => Post::published()->ordered()->get()
                    ->map(fn (Post $post) => [
                        'title' => $post->title,
                        'excerpt' => $post->excerpt,
                        'image' => $post->image_url,
                        'href' => $post->link ?: '#',
                    ])->values(),
            ],

            'contact' => [
                'eyebrow' => $contact['eyebrow'] ?? null,
                'title' => $contact['title'] ?? null,
                'callLabel' => $contact['call_label'] ?? null,
                'writeLabel' => $contact['write_label'] ?? null,
                'agentImage' => Media::url($contact['agent_image'] ?? null),
            ],

            'footer' => [
                'about' => $footer['about'] ?? null,
                'services' => $footer['services_links'] ?? [],
                'company' => collect($footer['company_links'] ?? [])
                    ->map(fn (array $link) => ['label' => $link['label'], 'to' => $link['href']])->values(),
                'social' => collect($footer['social_links'] ?? [])
                    ->map(fn (array $link) => [
                        'network' => Str::slug($link['label']),
                        'label' => Str::title($link['label']),
                        'href' => $link['href'],
                    ])->values(),
                'visionImage' => Media::url($footer['vision_image'] ?? null),
                'topLinks' => $footer['top_links'] ?? [],
            ],

            'stats' => [
                'eyebrow' => $stats['eyebrow'] ?? null,
                'title' => $stats['title'] ?? null,
                'items' => static::rows($stats['items'] ?? [], ['value', 'label', 'detail']),
            ],

            'clients' => [
                'eyebrow' => $clients['eyebrow'] ?? null,
                'title' => $clients['title'] ?? null,
                'text' => $clients['text'] ?? null,
                'image' => Media::url($clients['image'] ?? null),
                'names' => $clients['names'] ?? [],
            ],

            'aboutPage' => static::aboutPage($aboutPage),
            'servicesPage' => static::servicesPage($servicesPage),
            'credentialsPage' => static::credentialsPage($credentials),

            'projectsPage' => [
                'hero' => [
                    'title' => $pages['projects_hero_title'] ?? 'Our Projects',
                    'text' => $pages['projects_hero_text'] ?? null,
                ],
                'eyebrow' => $pages['projects_eyebrow'] ?? null,
                'title' => $pages['projects_title'] ?? null,
                'text' => $pages['projects_text'] ?? null,
                'filters' => static::filters($projects->pluck('category')),
                'trackRecord' => static::trackRecord(),
                'items' => $projects->map(fn (Project $project) => [
                    'title' => $project->title,
                    'category' => $project->category,
                    'location' => $project->location,
                    'client' => $project->client,
                    'year' => $project->year,
                    'value' => $project->contract_value,
                    'role' => $project->role,
                    'summary' => $project->summary,
                    'image' => $project->image_url,
                ])->values(),
            ],

            'galleryPage' => [
                'hero' => [
                    'title' => $pages['gallery_hero_title'] ?? 'Gallery',
                    'text' => $pages['gallery_hero_text'] ?? null,
                ],
                'eyebrow' => $pages['gallery_eyebrow'] ?? null,
                'title' => $pages['gallery_title'] ?? null,
                'text' => $pages['gallery_text'] ?? null,
                'filters' => static::filters($gallery->pluck('category')),
                'items' => $gallery->map(fn (GalleryImage $image) => [
                    'src' => $image->image_url,
                    'caption' => $image->caption,
                    'category' => $image->category,
                ])->values(),
            ],

            'contactPage' => [
                'hero' => [
                    'title' => $pages['contact_hero_title'] ?? 'Contact Us',
                    'text' => $pages['contact_hero_text'] ?? null,
                ],
                'cards' => [
                    [
                        'icon' => 'phone',
                        'title' => 'Call us',
                        'value' => $company['phone'] ?? null,
                        'href' => $company['phone_href'] ?? null,
                        'note' => $contact['call_label'] ?? null,
                    ],
                    [
                        'icon' => 'mail',
                        'title' => 'Email us',
                        'value' => $company['email'] ?? null,
                        'href' => isset($company['email']) ? 'mailto:'.$company['email'] : null,
                    ],
                    [
                        'icon' => 'map',
                        'title' => 'Visit us',
                        'value' => $company['address'] ?? null,
                    ],
                ],
                'mapQuery' => $contact['map_query'] ?? null,
            ],
        ];
    }

    private static function aboutPage(array $p): array
    {
        $leader = fn (string $key) => [
            'name' => $p["{$key}_name"] ?? null,
            'role' => $p["{$key}_role"] ?? null,
            'message' => static::paragraphs($p["{$key}_message"] ?? null),
            'image' => Media::url($p["{$key}_image"] ?? null),
        ];

        return [
            'hero' => ['title' => $p['hero_title'] ?? 'About Us', 'text' => $p['hero_text'] ?? null],
            'intro' => [
                'eyebrow' => $p['intro_eyebrow'] ?? null,
                'title' => $p['intro_title'] ?? null,
                'paragraphs' => static::paragraphs($p['intro_text'] ?? null),
                'image' => Media::url($p['intro_image'] ?? null),
            ],
            'leaders' => array_values(array_filter([$leader('chairman'), $leader('ceo')], fn ($l) => filled($l['name']))),
            'vision' => $p['vision'] ?? null,
            'mission' => $p['mission'] ?? null,
            'values' => static::rows($p['values'] ?? [], ['title', 'text']),
            'presence' => [
                'title' => $p['presence_title'] ?? null,
                'text' => $p['presence_text'] ?? null,
                'locations' => collect(static::rows($p['presence_locations'] ?? [], ['name', 'note', 'coords']))
                    ->map(function (array $row, int $i) {
                        // "26.28, 50.21" → lat / lng for the 3D map pin
                        [$lat, $lng] = array_pad(array_map('floatval', explode(',', (string) $row['coords'])), 2, null);
                        unset($row['coords']);

                        return $row + ['lat' => $lat ?: null, 'lng' => $lng ?: null, 'hq' => $i === 0];
                    })->values()->all(),
                'image' => Media::url($p['presence_image'] ?? null),
            ],
            'why' => [
                'title' => $p['why_title'] ?? null,
                'text' => $p['why_text'] ?? null,
                'points' => static::rows($p['why_points'] ?? [], ['title', 'text']),
            ],
            'resources' => [
                'title' => $p['resources_title'] ?? null,
                'paragraphs' => static::paragraphs($p['resources_text'] ?? null),
                'image' => Media::url($p['resources_image'] ?? null),
                'financial' => static::rows($p['financial_points'] ?? [], ['title', 'text']),
            ],
        ];
    }

    private static function servicesPage(array $p): array
    {
        return [
            'hero' => ['title' => $p['hero_title'] ?? 'Our Services', 'text' => $p['hero_text'] ?? null],
            'eyebrow' => $p['eyebrow'] ?? null,
            'title' => $p['title'] ?? null,
            'text' => $p['text'] ?? null,
            'items' => Service::published()->ordered()->get()->map(fn (Service $service) => [
                'id' => Str::slug($service->title),
                'title' => $service->title,
                'summary' => $service->summary,
                'image' => $service->image_url,
                // "Group: a, b, c" becomes a titled group; any other line is a single item.
                'groups' => collect($service->points ?? [])->map(function (string $line) {
                    if (preg_match('/^([^:]{2,60}):\s*(.+)$/', $line, $m)) {
                        return ['title' => trim($m[1]), 'items' => array_values(array_filter(array_map('trim', explode(str_contains($m[2], ';') ? ';' : ',', $m[2]))))];
                    }

                    return ['title' => null, 'items' => [$line]];
                })->values(),
            ])->values(),
            'fleet' => [
                'title' => $p['fleet_title'] ?? null,
                'text' => $p['fleet_text'] ?? null,
                'items' => static::rows($p['fleet_items'] ?? [], ['name', 'count']),
            ],
        ];
    }

    private static function credentialsPage(array $p): array
    {
        // Cards pick up their image by a keyword in the card title.
        $cardImages = [
            'local content' => $p['local_content_image'] ?? null,
            'contractors authority' => $p['sca_image'] ?? null,
            'd-u-n-s' => $p['duns_image'] ?? null,
            'cybersecurity' => $p['cyber_image'] ?? null,
        ];

        return [
            'hero' => ['title' => $p['hero_title'] ?? 'QHSE & Credentials', 'text' => $p['hero_text'] ?? null],
            'hse' => [
                'title' => $p['hse_title'] ?? null,
                'text' => $p['hse_text'] ?? null,
                'pillars' => static::rows($p['hse_pillars'] ?? [], ['title', 'text']),
                'stats' => static::rows($p['safety_stats'] ?? [], ['year', 'manhours', 'fai', 'mtc', 'lti', 'nearMiss', 'environmental', 'fatalities']),
            ],
            'iso' => [
                'title' => $p['iso_title'] ?? null,
                'text' => $p['iso_text'] ?? null,
                'items' => static::rows($p['iso_items'] ?? [], ['title', 'text']),
                'images' => array_values(array_filter([Media::url($p['iso_image1'] ?? null), Media::url($p['iso_image2'] ?? null)])),
            ],
            'quality' => [
                'text' => $p['qa_text'] ?? null,
                'assurance' => $p['qa_points'] ?? [],
                'control' => $p['qc_points'] ?? [],
                'culture' => $p['qa_culture'] ?? [],
            ],
            'sustainability' => [
                'title' => $p['sustainability_title'] ?? null,
                'text' => $p['sustainability_text'] ?? null,
                'sdgs' => static::rows($p['sdgs'] ?? [], ['number', 'title', 'text']),
                'csrText' => $p['csr_text'] ?? null,
                'csr' => static::rows($p['csr_pillars'] ?? [], ['title', 'text']),
                'csrImage' => Media::url($p['csr_image'] ?? null),
            ],
            'credentials' => collect(static::rows($p['credentials'] ?? [], ['title', 'detail', 'text']))
                ->map(function (array $card) use ($cardImages) {
                    $key = collect(array_keys($cardImages))->first(fn ($k) => str_contains(Str::lower($card['title'] ?? ''), $k));
                    $card['image'] = $key ? Media::url($cardImages[$key]) : null;

                    return $card;
                })->values(),
            'government' => $p['government'] ?? [],
            'governmentImage' => Media::url($p['government_image'] ?? null),
            'digital' => [
                'text' => $p['digital_text'] ?? null,
                'points' => static::rows($p['digital_points'] ?? [], ['title', 'text']),
            ],
            'recognition' => [
                'text' => $p['recognition_text'] ?? null,
                'images' => collect([
                    ['caption' => 'Projects & construction', 'key' => 'recognition_image1'],
                    ['caption' => 'QHSE achievements', 'key' => 'recognition_image2'],
                    ['caption' => 'Facility management', 'key' => 'recognition_image3'],
                ])->map(fn ($item) => ['caption' => $item['caption'], 'src' => Media::url($p[$item['key']] ?? null)])
                    ->filter(fn ($item) => $item['src'])->values(),
            ],
        ];
    }

    private static function trackRecord(): array
    {
        $records = TrackRecord::published()->ordered()->get()->groupBy('section');

        return collect(TrackRecord::SECTIONS)
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'rows' => ($records[$key] ?? collect())->map(fn (TrackRecord $r) => [
                    'title' => $r->title,
                    'client' => $r->client,
                    'location' => $r->location,
                    'scope' => $r->scope,
                    'year' => $r->year,
                    'value' => $r->contract_value,
                ])->values(),
            ])
            ->filter(fn (array $section) => $section['rows']->isNotEmpty())
            ->values()
            ->all();
    }

    /** Splits "a | b | c" lines into rows keyed by $keys. */
    private static function rows(array $lines, array $keys): array
    {
        return collect($lines)->map(function ($line) use ($keys) {
            $parts = array_map('trim', explode('|', (string) $line));
            $row = [];
            foreach ($keys as $i => $key) {
                $row[$key] = ($parts[$i] ?? '') !== '' ? $parts[$i] : null;
            }

            return $row;
        })->values()->all();
    }

    /** Splits text into paragraphs on blank lines. */
    private static function paragraphs(?string $text): array
    {
        return collect(preg_split('/\R\s*\R/', trim((string) $text)))->map(fn ($p) => trim($p))->filter()->values()->all();
    }

    private static function expertiseTiles(array $expertise): array
    {
        $tiles = [];

        foreach ([1, 2, 3] as $index) {
            if (filled($expertise["card{$index}_title"] ?? null)) {
                $tiles[] = [
                    'type' => 'card',
                    'icon' => $expertise["card{$index}_icon"] ?? 'bulb',
                    'title' => $expertise["card{$index}_title"],
                    'points' => $expertise["card{$index}_points"] ?? [],
                    'stat' => $expertise["card{$index}_stat"] ?? null,
                    'statLabel' => $expertise["card{$index}_stat_label"] ?? null,
                ];
            }

            $image = Media::url($expertise["image{$index}"] ?? null);

            if ($image) {
                $tiles[] = ['type' => 'image', 'image' => $image, 'alt' => ''];
            }
        }

        return $tiles;
    }

    private static function filters($categories): array
    {
        return collect($categories)->filter()->unique()->sort()->values()->prepend('All')->all();
    }
}
