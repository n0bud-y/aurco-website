<?php

/*
|--------------------------------------------------------------------------
| Editable site content
|--------------------------------------------------------------------------
| Every group below becomes a tab in the CRM's "Site content" screen and a
| block in the JSON the React site reads. Field types:
|   text | textarea | lines (one item per line) | pairs (Label | URL) |
|   image (upload) | select (options)
*/

return [
    'groups' => [

        'company' => [
            'label' => 'Company details',
            'description' => 'Used in the header, contact card, footer and WhatsApp button.',
            'fields' => [
                'name' => ['label' => 'Company name', 'type' => 'text', 'rules' => 'required|string|max:120'],
                'tagline' => ['label' => 'Tagline', 'type' => 'text', 'help' => 'e.g. Built on Trust. Driven by Excellence.'],
                'phone' => ['label' => 'Phone number', 'type' => 'text'],
                'phone_href' => ['label' => 'Phone link', 'type' => 'text', 'help' => 'For example tel:+966138944304'],
                'fax' => ['label' => 'Fax number', 'type' => 'text'],
                'email' => ['label' => 'Email address', 'type' => 'text', 'rules' => 'nullable|email|max:160'],
                'whatsapp' => ['label' => 'WhatsApp link', 'type' => 'text', 'help' => 'For example https://wa.me/966138944304'],
                'address' => ['label' => 'Office address', 'type' => 'textarea'],
                'legal_name' => ['label' => 'Legal name (copyright line)', 'type' => 'text'],
                'logo' => ['label' => 'Logo', 'type' => 'image', 'help' => 'PNG with a transparent background works best.'],
            ],
        ],

        'hero' => [
            'label' => 'Home hero',
            'description' => 'The first screen of the home page.',
            'fields' => [
                'eyebrow' => ['label' => 'Small label above the headline', 'type' => 'text'],
                'title' => ['label' => 'Headline', 'type' => 'textarea'],
                'text' => ['label' => 'Paragraph', 'type' => 'textarea'],
                'image' => ['label' => 'Background photo', 'type' => 'image'],
            ],
        ],

        'stats' => [
            'label' => 'Key figures',
            'description' => 'The “AURCO at a glance” numbers on the home and About pages.',
            'fields' => [
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'text'],
                'items' => ['label' => 'Figures', 'type' => 'lines', 'help' => 'One per line: Number | Label | optional detail. e.g. 150+ | Projects completed'],
            ],
        ],

        'about' => [
            'label' => 'About section',
            'fields' => [
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'text' => ['label' => 'Paragraph', 'type' => 'textarea'],
                'features' => ['label' => 'Checklist', 'type' => 'lines', 'help' => 'One item per line.'],
                'support_label' => ['label' => 'Phone label', 'type' => 'text'],
                'support_phone' => ['label' => 'Phone number shown', 'type' => 'text'],
                'support_phone_href' => ['label' => 'Phone link', 'type' => 'text'],
                'image' => ['label' => 'Photo', 'type' => 'image'],
            ],
        ],

        'services_section' => [
            'label' => 'Services section',
            'description' => 'Headings only — the four cards are managed under Services.',
            'fields' => [
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'text' => ['label' => 'Intro paragraph', 'type' => 'textarea'],
                'cta_label' => ['label' => 'Button label', 'type' => 'text'],
            ],
        ],

        'expertise' => [
            'label' => 'Why choose us',
            'description' => 'Three cards and three photos, shown alternating in the grid.',
            'fields' => [
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'text' => ['label' => 'Intro paragraph', 'type' => 'textarea'],

                'card1_title' => ['label' => 'Card 1 — title', 'type' => 'text'],
                'card1_icon' => ['label' => 'Card 1 — icon', 'type' => 'select', 'options' => ['bulb' => 'Lightbulb', 'shield' => 'Badge', 'gear' => 'Gear']],
                'card1_points' => ['label' => 'Card 1 — list', 'type' => 'lines'],
                'card1_stat' => ['label' => 'Card 1 — number', 'type' => 'text'],
                'card1_stat_label' => ['label' => 'Card 1 — number label', 'type' => 'text'],

                'card2_title' => ['label' => 'Card 2 — title', 'type' => 'text'],
                'card2_icon' => ['label' => 'Card 2 — icon', 'type' => 'select', 'options' => ['bulb' => 'Lightbulb', 'shield' => 'Badge', 'gear' => 'Gear']],
                'card2_points' => ['label' => 'Card 2 — list', 'type' => 'lines'],
                'card2_stat' => ['label' => 'Card 2 — number', 'type' => 'text'],
                'card2_stat_label' => ['label' => 'Card 2 — number label', 'type' => 'text'],

                'card3_title' => ['label' => 'Card 3 — title', 'type' => 'text'],
                'card3_icon' => ['label' => 'Card 3 — icon', 'type' => 'select', 'options' => ['bulb' => 'Lightbulb', 'shield' => 'Badge', 'gear' => 'Gear']],
                'card3_points' => ['label' => 'Card 3 — list', 'type' => 'lines'],
                'card3_stat' => ['label' => 'Card 3 — number', 'type' => 'text'],
                'card3_stat_label' => ['label' => 'Card 3 — number label', 'type' => 'text'],

                'image1' => ['label' => 'Photo 1', 'type' => 'image'],
                'image2' => ['label' => 'Photo 2', 'type' => 'image'],
                'image3' => ['label' => 'Photo 3', 'type' => 'image'],
            ],
        ],

        'projects_section' => [
            'label' => 'Projects section',
            'description' => 'Headings only — the cards come from Projects marked "featured".',
            'fields' => [
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'text' => ['label' => 'Intro paragraph', 'type' => 'textarea'],
                'cta_label' => ['label' => 'Button label', 'type' => 'text'],
            ],
        ],

        'cta_banner' => [
            'label' => 'Call to action band',
            'fields' => [
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'text' => ['label' => 'Paragraph', 'type' => 'textarea'],
                'cta_label' => ['label' => 'Button label', 'type' => 'text'],
                'image' => ['label' => 'Cut-out photo', 'type' => 'image'],
            ],
        ],

        'testimonials_section' => [
            'label' => 'Testimonials section',
            'fields' => [
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'text' => ['label' => 'Intro paragraph', 'type' => 'textarea'],
            ],
        ],

        'faq_section' => [
            'label' => 'FAQ section',
            'fields' => [
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'text' => ['label' => 'Intro paragraph', 'type' => 'textarea'],
                'image1' => ['label' => 'Collage photo 1 (large, top left)', 'type' => 'image'],
                'image2' => ['label' => 'Collage photo 2 (small, top right)', 'type' => 'image'],
                'image3' => ['label' => 'Collage photo 3 (large, bottom left)', 'type' => 'image'],
                'image4' => ['label' => 'Collage photo 4 (small, bottom right)', 'type' => 'image'],
            ],
        ],

        'blog_section' => [
            'label' => 'Blog section',
            'fields' => [
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'text' => ['label' => 'Intro paragraph', 'type' => 'textarea'],
            ],
        ],

        'contact_section' => [
            'label' => 'Contact section',
            'fields' => [
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'call_label' => ['label' => 'Phone label on the red card', 'type' => 'text'],
                'write_label' => ['label' => 'Email label on the red card', 'type' => 'text'],
                'map_query' => ['label' => 'Map search text', 'type' => 'text'],
                'agent_image' => ['label' => 'Photo on the red card', 'type' => 'image'],
            ],
        ],

        'footer' => [
            'label' => 'Footer',
            'fields' => [
                'about' => ['label' => 'Short description', 'type' => 'textarea'],
                'services_links' => ['label' => '"Our Services" links', 'type' => 'pairs', 'help' => 'One per line: Label | /url'],
                'company_links' => ['label' => '"Company" links', 'type' => 'pairs'],
                'social_links' => ['label' => 'Social links', 'type' => 'pairs', 'help' => 'Use instagram, facebook, x, youtube or linkedin as the label.'],
                'top_links' => ['label' => 'Top bar links', 'type' => 'pairs', 'help' => 'The small links above the header. One per line: Label | /url'],
                'vision_image' => ['label' => 'Vision 2030 logo', 'type' => 'image'],
            ],
        ],

        'about_page' => [
            'label' => 'About page',
            'description' => 'Everything on /about: who we are, leadership, vision, values, presence and resources.',
            'fields' => [
                'hero_title' => ['label' => 'Banner title', 'type' => 'text'],
                'hero_text' => ['label' => 'Banner text', 'type' => 'textarea'],
                'intro_eyebrow' => ['label' => 'Who we are — small label', 'type' => 'text'],
                'intro_title' => ['label' => 'Who we are — heading', 'type' => 'textarea'],
                'intro_text' => ['label' => 'Who we are — text', 'type' => 'textarea', 'help' => 'Leave a blank line between paragraphs.'],
                'intro_image' => ['label' => 'Who we are — photo', 'type' => 'image'],
                'chairman_name' => ['label' => 'Chairman — name', 'type' => 'text'],
                'chairman_role' => ['label' => 'Chairman — title', 'type' => 'text'],
                'chairman_message' => ['label' => 'Chairman — message', 'type' => 'textarea', 'help' => 'Leave a blank line between paragraphs.'],
                'chairman_image' => ['label' => 'Chairman — photo', 'type' => 'image'],
                'ceo_name' => ['label' => 'CEO — name', 'type' => 'text'],
                'ceo_role' => ['label' => 'CEO — title', 'type' => 'text'],
                'ceo_message' => ['label' => 'CEO — message', 'type' => 'textarea', 'help' => 'Leave a blank line between paragraphs.'],
                'ceo_image' => ['label' => 'CEO — photo', 'type' => 'image'],
                'vision' => ['label' => 'Vision', 'type' => 'textarea'],
                'mission' => ['label' => 'Mission', 'type' => 'textarea'],
                'values' => ['label' => 'Core values', 'type' => 'lines', 'help' => 'One per line: Value | short description'],
                'presence_title' => ['label' => 'Regional presence — heading', 'type' => 'text'],
                'presence_text' => ['label' => 'Regional presence — text', 'type' => 'textarea'],
                'presence_locations' => ['label' => 'Locations', 'type' => 'lines', 'help' => 'One per line: City | note (e.g. Khobar | Head office)'],
                'presence_image' => ['label' => 'Regional presence — map', 'type' => 'image'],
                'why_title' => ['label' => 'Why choose AURCO — heading', 'type' => 'text'],
                'why_text' => ['label' => 'Why choose AURCO — text', 'type' => 'textarea'],
                'why_points' => ['label' => 'Why choose AURCO — points', 'type' => 'lines', 'help' => 'One per line: Title | text'],
                'resources_title' => ['label' => 'Resources — heading', 'type' => 'text'],
                'resources_text' => ['label' => 'Resources — text', 'type' => 'textarea', 'help' => 'Leave a blank line between paragraphs.'],
                'resources_image' => ['label' => 'Resources — manpower & equipment chart', 'type' => 'image'],
                'financial_points' => ['label' => 'Financial standing — points', 'type' => 'lines', 'help' => 'One per line: Title | text'],
            ],
        ],

        'clients' => [
            'label' => 'Clients',
            'description' => 'The client logo wall shown on the home and About pages.',
            'fields' => [
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'text' => ['label' => 'Intro paragraph', 'type' => 'textarea'],
                'image' => ['label' => 'Logo wall', 'type' => 'image'],
                'names' => ['label' => 'Client names', 'type' => 'lines', 'help' => 'Used as the image description for screen readers and search engines.'],
            ],
        ],

        'services_page' => [
            'label' => 'Services page',
            'description' => 'Text around the business lines on /services. The business lines themselves are managed under Services.',
            'fields' => [
                'hero_title' => ['label' => 'Banner title', 'type' => 'text'],
                'hero_text' => ['label' => 'Banner text', 'type' => 'textarea'],
                'eyebrow' => ['label' => 'Small label', 'type' => 'text'],
                'title' => ['label' => 'Heading', 'type' => 'textarea'],
                'text' => ['label' => 'Intro paragraph', 'type' => 'textarea'],
                'fleet_title' => ['label' => 'Fleet — heading', 'type' => 'text'],
                'fleet_text' => ['label' => 'Fleet — text', 'type' => 'textarea'],
                'fleet_items' => ['label' => 'Fleet — units', 'type' => 'lines', 'help' => 'One per line: Equipment | quantity'],
            ],
        ],

        'credentials_page' => [
            'label' => 'QHSE & credentials page',
            'description' => 'Everything on /credentials: HSE, safety statistics, ISO, QA/QC, sustainability and registrations.',
            'fields' => [
                'hero_title' => ['label' => 'Banner title', 'type' => 'text'],
                'hero_text' => ['label' => 'Banner text', 'type' => 'textarea'],
                'hse_title' => ['label' => 'HSE — heading', 'type' => 'text'],
                'hse_text' => ['label' => 'HSE — text', 'type' => 'textarea'],
                'hse_pillars' => ['label' => 'HSE — pillars', 'type' => 'lines', 'help' => 'One per line: Title | text'],
                'safety_stats' => ['label' => 'Annual safety statistics', 'type' => 'lines', 'help' => 'One row per year: Year | Man-hours | First aid cases | Medical treatment cases | LTI | Near miss | Environmental incidents | Fatalities'],
                'iso_title' => ['label' => 'Certifications — heading', 'type' => 'text'],
                'iso_text' => ['label' => 'Certifications — text', 'type' => 'textarea'],
                'iso_items' => ['label' => 'Certifications — list', 'type' => 'lines', 'help' => 'One per line: Standard | description'],
                'iso_image1' => ['label' => 'Certificate photo 1', 'type' => 'image'],
                'iso_image2' => ['label' => 'Certificate photo 2', 'type' => 'image'],
                'qa_text' => ['label' => 'Quality — text', 'type' => 'textarea'],
                'qa_points' => ['label' => 'Quality assurance — focus areas', 'type' => 'lines'],
                'qc_points' => ['label' => 'Quality control — focus areas', 'type' => 'lines'],
                'qa_culture' => ['label' => 'QA/QC culture', 'type' => 'lines'],
                'sustainability_title' => ['label' => 'Sustainability — heading', 'type' => 'text'],
                'sustainability_text' => ['label' => 'Sustainability — text', 'type' => 'textarea'],
                'sdgs' => ['label' => 'UN SDGs', 'type' => 'lines', 'help' => 'One per line: Goal number | Goal | how AURCO contributes'],
                'csr_text' => ['label' => 'CSR — text', 'type' => 'textarea'],
                'csr_pillars' => ['label' => 'CSR — pillars', 'type' => 'lines', 'help' => 'One per line: Title | text'],
                'csr_image' => ['label' => 'CSR — image', 'type' => 'image'],
                'credentials' => ['label' => 'Registrations & compliance cards', 'type' => 'lines', 'help' => 'One per line: Title | key detail | text'],
                'local_content_image' => ['label' => 'Card image — local content', 'type' => 'image'],
                'sca_image' => ['label' => 'Card image — SCA', 'type' => 'image'],
                'duns_image' => ['label' => 'Card image — D-U-N-S', 'type' => 'image'],
                'cyber_image' => ['label' => 'Card image — cybersecurity', 'type' => 'image'],
                'government' => ['label' => 'Government registrations', 'type' => 'lines'],
                'government_image' => ['label' => 'Government certificates image', 'type' => 'image'],
                'digital_text' => ['label' => 'Digital — text', 'type' => 'textarea'],
                'digital_points' => ['label' => 'Digital — points', 'type' => 'lines', 'help' => 'One per line: Title | text'],
                'recognition_text' => ['label' => 'Recognition — text', 'type' => 'textarea'],
                'recognition_image1' => ['label' => 'Recognition — projects certificates', 'type' => 'image'],
                'recognition_image2' => ['label' => 'Recognition — QHSE certificates', 'type' => 'image'],
                'recognition_image3' => ['label' => 'Recognition — facility management certificates', 'type' => 'image'],
            ],
        ],

        'pages' => [
            'label' => 'Inner pages',
            'description' => 'Banner and intro text for the Projects, Gallery and Contact pages.',
            'fields' => [
                'projects_hero_title' => ['label' => 'Projects — banner title', 'type' => 'text'],
                'projects_hero_text' => ['label' => 'Projects — banner text', 'type' => 'textarea'],
                'projects_eyebrow' => ['label' => 'Projects — small label', 'type' => 'text'],
                'projects_title' => ['label' => 'Projects — heading', 'type' => 'textarea'],
                'projects_text' => ['label' => 'Projects — intro paragraph', 'type' => 'textarea'],
                'gallery_hero_title' => ['label' => 'Gallery — banner title', 'type' => 'text'],
                'gallery_hero_text' => ['label' => 'Gallery — banner text', 'type' => 'textarea'],
                'gallery_eyebrow' => ['label' => 'Gallery — small label', 'type' => 'text'],
                'gallery_title' => ['label' => 'Gallery — heading', 'type' => 'textarea'],
                'gallery_text' => ['label' => 'Gallery — intro paragraph', 'type' => 'textarea'],
                'contact_hero_title' => ['label' => 'Contact — banner title', 'type' => 'text'],
                'contact_hero_text' => ['label' => 'Contact — banner text', 'type' => 'textarea'],
            ],
        ],
    ],
];
