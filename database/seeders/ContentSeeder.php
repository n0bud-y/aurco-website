<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\TrackRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Fills the CRM with the content of the AURCO Company Profile 2026-27.
 * Safe to re-run: existing settings groups and rows are left untouched.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->publishStubImages();
        $this->seedSettings();
        $this->seedProjects();
        $this->seedTrackRecord();
        $this->seedGallery();
        $this->seedServices();
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedPosts();
    }

    /** Copies the bundled images (including the profile/ folder) into storage/app/public/seed. */
    private function publishStubImages(): void
    {
        $source = database_path('seeders/stubs/images');

        if (! File::isDirectory($source)) {
            return;
        }

        foreach (File::allFiles($source) as $file) {
            $target = 'seed/'.str_replace('\\', '/', $file->getRelativePathname());

            if (! Storage::disk('public')->exists($target)) {
                Storage::disk('public')->put($target, File::get($file->getPathname()));
            }
        }
    }

    /** Path of a published stub image; falls back to the .jpg/.png original when no .webp exists. */
    private function img(string $name): ?string
    {
        $base = preg_replace('/\.webp$/', '', $name);

        foreach (array_unique([$name, "$base.jpg", "$base.png"]) as $candidate) {
            if (Storage::disk('public')->exists("seed/$candidate")) {
                return "seed/$candidate";
            }
        }

        return null;
    }

    private function profile(string $name): ?string
    {
        return $this->img("profile/$name.webp");
    }

    private function seedSettings(): void
    {
        $groups = [
            'company' => [
                'name' => 'AURCO Contracting',
                'tagline' => 'Built on Trust. Driven by Excellence.',
                'phone' => '+966 13 894 4304',
                'phone_href' => 'tel:+966138944304',
                'fax' => '+966 13 896 1712',
                'email' => 'info@aurcontracting.com',
                'whatsapp' => 'https://wa.me/966138944304',
                'address' => 'Office # 402, 408, 409 & 410, 4th Floor, Jarir Plaza Corniche, Al Khobar 34427, KSA',
                'legal_name' => 'Attiq Ur Rehman Contracting Co. (AURCO).',
                'logo' => $this->img('logo-aurco.webp'),
            ],
            'hero' => [
                'eyebrow' => 'Built on Trust. Driven by Excellence.',
                'title' => 'Build. Equip. Operate.',
                'text' => 'Since 2009, AURCO has delivered 150+ projects for Saudi Aramco, NEOM, SWCC and the Kingdom’s leading contractors — with 10 million+ safe man-hours and zero lost-time incidents.',
                'image' => $this->img('hero-bg.webp'),
            ],
            'stats' => [
                'eyebrow' => 'AURCO at a glance',
                'title' => 'Key facts and figures',
                'items' => [
                    '150+ | Projects completed',
                    '1B+ SAR | Executed contracts value',
                    '10+ | Industries served | Oil & gas, energy, power, water, waste management, mining',
                    '10M+ | Safe man-hours | Zero lost-time incidents',
                    '17+ | Years of experience | Established 2009',
                    '7+ | Locations | Khobar, Jubail, Riyadh, Yanbu, Jazan, Jafurah, NEOM, Turaif',
                    '800+ | Workforce',
                    '200+ | Equipment assets',
                ],
            ],
            'about' => [
                'eyebrow' => 'About AURCO',
                'title' => 'A leading Saudi contractor since 2009',
                'text' => 'Attiq Ur Rehman Contracting Co. (AURCO) delivers integrated engineering, construction, facility management and equipment rental solutions across the Kingdom. With expertise across Oil & Gas, Petrochemical, Power & Utilities, Infrastructure, Industrial, Commercial and Government sectors, we combine skilled teams, technical excellence and disciplined execution to deliver safe, sustainable and value-driven solutions.',
                'features' => [
                    '17+ years, 7+ locations across the Kingdom',
                    'ISO 9001, ISO 14001 & ISO 45001 certified',
                    '10M+ safe man-hours with zero LTI',
                ],
                'support_label' => 'Call our head office',
                'support_phone' => '+966 13 894 4304',
                'support_phone_href' => 'tel:+966138944304',
                'image' => $this->profile('about-hse-team'),
            ],
            'services_section' => [
                'eyebrow' => 'Our businesses',
                'title' => 'One partner for construction, equipment and facilities',
                'text' => 'Built from the ground up over 17+ years, AURCO operates across three dynamic business lines, supported by skilled teams and disciplined execution.',
                'cta_label' => 'Explore Our Services',
            ],
            'expertise' => [
                'eyebrow' => 'Why choose AURCO',
                'title' => 'Built on trust. Driven by excellence.',
                'text' => 'Our multidisciplinary expertise, skilled workforce and modern equipment enable us to deliver complete turnkey solutions — on time, within budget and to the highest standards.',
                'card1_title' => 'Projects & Construction',
                'card1_icon' => 'bulb',
                'card1_points' => [
                    'Site development & early works',
                    'Industrial & civil construction',
                    'Infrastructure, roads & security fencing',
                    'Mechanical, piping & plant services',
                    'Steel structures & modular facilities',
                ],
                'card1_stat' => '150+',
                'card1_stat_label' => 'Projects completed',
                'card2_title' => 'Equipment Rental',
                'card2_icon' => 'gear',
                'card2_points' => [
                    'Cranes, man lifts & forklifts',
                    'Excavators, dozers, graders & loaders',
                    'Trailers, tankers, buses & pickups',
                    'Generators, compressors & tower lights',
                    'Backed by maintenance & technical support',
                ],
                'card2_stat' => '200+',
                'card2_stat_label' => 'Equipment assets',
                'card3_title' => 'Safety, Quality & FM',
                'card3_icon' => 'shield',
                'card3_points' => [
                    'ISO 9001, 14001 & 45001 certified',
                    'Zero LTI and zero fatalities, 2022–2026',
                    'Hard & soft facility management',
                    'Catering for camps and site offices',
                    '800+ strong workforce',
                ],
                'card3_stat' => '10M+',
                'card3_stat_label' => 'Safe man-hours, zero LTI',
                'image1' => $this->profile('why-projects'),
                'image2' => $this->profile('why-equipment'),
                'image3' => $this->profile('why-safety'),
            ],
            'projects_section' => [
                'eyebrow' => 'Our projects',
                'title' => 'Delivering for the Kingdom’s leading organizations',
                'text' => 'A selection of industrial, infrastructure and modular projects completed for Saudi Aramco, NEOM, SWCC and major EPC contractors.',
                'cta_label' => 'View All Projects',
            ],
            'clients' => [
                'eyebrow' => 'Our clients',
                'title' => 'Trusted by the Kingdom’s leading organizations',
                'text' => 'Government entities, national champions and international EPC contractors rely on AURCO across Saudi Arabia.',
                'image' => $this->profile('clients-logo-wall'),
                'names' => [
                    'Saudi Aramco', 'NEOM', 'Saudi Electricity Company', 'SABIC', 'Ma’aden', 'SEVEN', 'SATORP',
                    'King Salman Energy Park (SPARK)', 'Jeddah Central', 'King Fahd Causeway Authority', 'Ministry of Energy',
                    'The Red Sea Development Company', 'Royal Commission for Jubail & Yanbu', 'Royal Commission for AlUla',
                    'Saline Water Conversion Corporation', 'Saipem', 'Acciona', 'JIGPC', 'Tecnicas Reunidas', 'Air Products',
                    'SICIM', 'McDermott', 'Caterpillar', 'JGC Arabia', 'Samsung E&A', 'Hyundai E&C', 'Daewoo E&C', 'Hanwha E&C',
                    'GS Construction Arabia', 'Cristal', 'Kent', 'Al Reef Sugar Refinery', 'Dubaib & Sulaim', 'L&T Energy Hydrocarbon',
                    'CRCC', 'CCC', 'Petrojet', 'Red Sea Housing Services', 'Naizak', 'SGC', 'NGMSA', 'SSEM', 'Al Kifah Contracting',
                    'Azmeel Contracting', 'RTCC', 'Al Yamama',
                ],
            ],
            'cta_banner' => [
                'title' => 'Let’s build something great together!',
                'text' => 'From early works to facility management, partner with AURCO for safe, reliable delivery anywhere in the Kingdom.',
                'cta_label' => 'Request a Quote',
                'image' => $this->img('cta-engineer.webp'),
            ],
            'testimonials_section' => [
                'eyebrow' => 'Client recognition',
                'title' => 'What our clients say about us',
                'text' => 'Excerpts from letters and certificates of appreciation issued to AURCO by clients and main contractors.',
            ],
            'faq_section' => [
                'eyebrow' => 'FAQs',
                'title' => 'Got questions? We’ve got answers',
                'text' => 'Quick answers about our services, coverage and credentials. Can’t find what you need? Get in touch with our team.',
                'image1' => $this->profile('faq-workforce'),
                'image2' => $this->profile('faq-hydrotest'),
                'image3' => $this->profile('faq-excavation'),
                'image4' => $this->profile('faq-modular-night'),
            ],
            'blog_section' => [
                'eyebrow' => 'Newsroom',
                'title' => 'Latest from AURCO',
                'text' => 'Milestones in safety, quality, compliance and digital transformation.',
            ],
            'contact_section' => [
                'eyebrow' => 'Contact us',
                'title' => 'Get in touch with us',
                'call_label' => 'Call Our Head Office',
                'write_label' => 'Write To Us',
                'map_query' => '26.2797,50.2088',
                'agent_image' => $this->img('contact-agent.webp'),
            ],
            'footer' => [
                'about' => 'Attiq Ur Rehman Contracting Co. (AURCO) is a Saudi contractor established in 2009, delivering construction, equipment rental and facility management across the Kingdom.',
                'services_links' => [
                    ['label' => 'Projects & Construction', 'href' => '/services#projects-construction'],
                    ['label' => 'Rental Equipment', 'href' => '/services#rental-equipment'],
                    ['label' => 'Facility Management', 'href' => '/services#facility-management'],
                    ['label' => 'Steel & Modular Solutions', 'href' => '/services#steel-fabrication-modular-solutions'],
                    ['label' => 'Health and Safety', 'href' => '/credentials#hse'],
                    ['label' => 'Quality Assurance', 'href' => '/credentials#quality'],
                ],
                'company_links' => [
                    ['label' => 'About Us', 'href' => '/about'],
                    ['label' => 'Vision and Mission', 'href' => '/about#vision'],
                    ['label' => 'Our Clients', 'href' => '/about#clients'],
                    ['label' => 'Certificates & Awards', 'href' => '/credentials#recognition'],
                    ['label' => 'Newsroom', 'href' => '/#blog'],
                    ['label' => 'FAQs', 'href' => '/#faq'],
                ],
                'social_links' => [
                    ['label' => 'linkedin', 'href' => 'https://www.linkedin.com/aurco'],
                    ['label' => 'facebook', 'href' => 'https://www.facebook.com/AURCO/'],
                ],
                'top_links' => [
                    ['label' => 'Newsroom', 'href' => '/#blog'],
                    ['label' => 'Resources', 'href' => '/credentials'],
                    ['label' => 'Careers', 'href' => 'mailto:info@aurcontracting.com?subject=Careers'],
                    ['label' => 'AURCO Websites', 'href' => 'https://www.aurco.com'],
                ],
                'vision_image' => $this->img('vision-2030.webp'),
            ],
            'about_page' => [
                'hero_title' => 'About AURCO',
                'hero_text' => 'Built on Trust. Driven by Excellence. A Saudi contractor serving the Kingdom’s most important industrial and infrastructure sectors since 2009.',
                'intro_eyebrow' => 'Who we are',
                'intro_title' => 'Integrated engineering, construction, facility management and equipment rental',
                'intro_text' => "Attiq Ur Rehman Contracting Co. (AURCO) is a leading Saudi contracting company established in 2009, delivering integrated engineering, construction, facility management, and equipment rental solutions across the Kingdom.\n\nWith expertise across Oil & Gas, Petrochemical, Power & Utilities, Infrastructure, Industrial, Commercial, and Government sectors, AURCO combines skilled teams, technical excellence, and disciplined execution to deliver safe, sustainable, and value driven solutions.\n\nAt AURCO, we deliver engineering and construction excellence through innovative project execution, technical expertise, and disciplined project management. Our commitment to quality, safety, and operational efficiency enables us to deliver resilient infrastructure and successful outcomes for clients across diverse industries.",
                'intro_image' => $this->profile('business-projects'),
                'chairman_name' => 'Muhammad Yaqoob',
                'chairman_role' => 'President & Founder',
                'chairman_message' => "Since AURCO’s establishment in 2009, our journey has been guided by a clear purpose: to build an organization founded on trust, integrity, and long-term value. What began with a strong vision has grown into a company serving some of the Kingdom’s most important industrial and infrastructure sectors.\n\nOur progress reflects the dedication of our people, the confidence of our clients and partners, and our commitment to responsible growth. As Saudi Arabia continues its remarkable transformation under Vision 2030, AURCO remains focused on strengthening its capabilities, developing local talent, and contributing meaningfully to the Kingdom’s sustainable future.\n\nWe look ahead with confidence, committed to building lasting partnerships and creating a legacy of excellence for generations to come.",
                'chairman_image' => $this->profile('leader-chairman'),
                'ceo_name' => 'Attiq Ur Rehman',
                'ceo_role' => 'Chief Executive Officer',
                'ceo_message' => "For over 17 years, Attiq Ur Rehman Contracting Co. (AURCO) has been committed to delivering quality, reliability, and excellence across the Kingdom of Saudi Arabia.\n\nThrough our expertise, dedicated workforce, and strong operational capabilities, we have contributed to significant projects supporting the Kingdom’s growth and the goals of Saudi Vision 2030. We remain committed to professionalism, safety, transparency, and building lasting partnerships by delivering exceptional value to our clients.",
                'ceo_image' => $this->profile('leader-ceo'),
                'vision' => 'To establish AURCO as the benchmark for construction excellence in the GCC Region recognized for delivering complex projects with superior quality, advanced engineering, unwavering reliability, sustainable practices, and a commitment to shaping resilient, modern infrastructure for generations to come.',
                'mission' => 'To consistently deliver high performance construction services through disciplined project management, cutting edge technology, and a culture of safety and accountability; to strengthen client trust by exceeding expectations; and to contribute to national and regional development by building sustainable, innovative, and impactful infrastructure.',
                'values' => [
                    'Integrity | We act with honesty and accountability',
                    'Excellence | We deliver quality without compromise',
                    'Safety | Safety is at the core of everything we do',
                    'Our People | Empowering our people to grow',
                    'Customer Focus | Our clients’ success is our priority',
                    'Innovation | We continuously improve and adapt',
                    'Teamwork | Strong partnerships drive better results',
                    'Sustainability | Building responsibly, supporting Vision 2030',
                ],
                'presence_title' => 'Our regional presence',
                'presence_text' => 'AURCO operates across Saudi Arabia with strong networks in multiple regions, enabling closer collaboration, faster response, and efficient project execution nationwide.',
                'presence_locations' => [
                    'Khobar | Head office | 26.28, 50.21',
                    'Jubail | | 27.01, 49.66',
                    'Riyadh | | 24.71, 46.68',
                    'Yanbu | | 24.09, 38.06',
                    'Jazan | | 16.89, 42.57',
                    'Jafurah | | 25.45, 49.45',
                    'NEOM | | 27.95, 35.30',
                    'Turaif | | 31.67, 38.66',
                ],
                'presence_image' => $this->profile('regional-presence-map'),
                'why_title' => 'Why choose AURCO',
                'why_text' => 'AURCO is committed to quality, safety, and client satisfaction. Our multidisciplinary expertise, skilled workforce, and modern equipment enable us to deliver complete turnkey solutions tailored to each project. By combining technical excellence with efficient project management and strict quality control, we consistently deliver on time, within budget, and to the highest standards, making us a trusted partner across Saudi Arabia.',
                'why_points' => [
                    'Quality Assurance | Committed to the highest standards in quality and workmanship.',
                    'Safety First | Prioritizing safety in every project, every step of the way.',
                    'Reliable Partner | Delivering on our promises with integrity, transparency and accountability.',
                    'Experienced Workforce | Skilled professionals driving excellence across disciplines.',
                    'Our Equipment | Utilizing advanced equipment and technology for superior results.',
                    'Turnkey Solutions | End-to-end solutions tailored to meet unique project requirements.',
                ],
                'resources_title' => 'Company resources',
                'resources_text' => "AURCO’s success is supported by a highly qualified workforce, experienced project managers, skilled engineers, certified safety professionals, and modern construction equipment. Our strong procurement network, advanced project management capabilities, and well-established operational infrastructure enable us to efficiently mobilize resources and execute projects of varying scales.\n\nBy investing in our people, equipment, and technology, we ensure that every project is delivered with precision, efficiency, and uncompromising quality.",
                'resources_image' => $this->profile('manpower-equipment'),
                'financial_points' => [
                    'Financial Capacity | Sound financial and working-capital management supporting project execution and operational commitments.',
                    'Project Delivery Capacity | Proven capability to mobilize manpower, equipment, materials and resources for projects of varying scale and complexity.',
                    'Banking & Guarantees | Established banking relationships and capability to support contractual requirements, including guarantees and project-related financial instruments.',
                    'Commercial Governance | Structured cost control, cash-flow monitoring, budgeting and commercial management practices supporting sustainable growth.',
                ],
            ],
            'services_page' => [
                'hero_title' => 'Our Services',
                'hero_text' => 'Three business lines — Projects & Construction, Rental Equipment and Facility Management — supported by in-house steel fabrication and modular solutions.',
                'eyebrow' => 'What we do',
                'title' => 'End-to-end capability, from early works to operations',
                'text' => 'Built from the ground up over 17+ years, AURCO has evolved through hard-earned project experience, continuous capability development and strategic partnerships, strengthening its ability to deliver across diverse clients, industries and project environments.',
                'fleet_title' => 'Our equipment fleet',
                'fleet_text' => '200+ well-maintained units — 52% earthmoving, 20% transport & tankers, 18% lifting & material handling and 10% support equipment.',
                'fleet_items' => [
                    'Dump trucks | 40', 'Double cabin pickups | 25', 'Excavators | 23', 'Welding generators | 20',
                    'Skid loaders | 16', 'Boom trucks | 15', 'JCB backhoe loaders | 14', 'Water tankers | 12', 'Coasters | 12',
                    'Graders | 10', 'Diesel tankers | 10', 'Forklifts | 10', 'Buses | 10', 'Roller compactors | 8',
                    'Dyna trucks | 8', 'Wheel loaders | 5', 'Dozers | 5', 'Cranes | 5', 'Air compressors | 4',
                ],
            ],
            'credentials_page' => [
                'hero_title' => 'QHSE & Credentials',
                'hero_text' => 'Certified management systems, a proven safety record and the registrations that let our clients work with us with confidence.',
                'hse_title' => 'Health, Safety & Environment',
                'hse_text' => 'Health, Safety, and Environmental excellence is an integral part of AURCO’s operational philosophy. Through our Safety Policy, ISO certified management systems, and Safety Appreciation and Recognition programs, we are committed to maintaining a safe and healthy working environment for our employees, clients, subcontractors, and communities.',
                'hse_pillars' => [
                    'Proactive | Proactive risk assessments, continuous safety training, and strict compliance with local and international regulations.',
                    'Culture | We promote a culture in which everyone is accountable for their own safety.',
                    'Responsible | Regulatory compliance, safety training, and responsible risk assessment.',
                    'Environmental | We reduce our influence on the environment and safeguard natural resources by using environmentally friendly techniques.',
                ],
                'safety_stats' => [
                    '2026 | 1,521,260 | 11 | 1 | 0 | 2 | 0 | 0',
                    '2025 | 2,232,324 | 10 | 0 | 0 | 5 | 0 | 0',
                    '2024 | 1,766,832 | 6 | 0 | 0 | 3 | 0 | 0',
                    '2023 | 1,206,106 | 8 | 0 | 0 | 1 | 0 | 0',
                    '2022 | 1,013,410 | 10 | 0 | 0 | 2 | 0 | 0',
                ],
                'iso_title' => 'Certified management systems',
                'iso_text' => 'Our integrated management system is certified for project management of general and structural civil works, electro-mechanical works, heavy equipment rentals, structural steel fabrication, total integrated facility management, and civil infrastructure and underground utilities.',
                'iso_items' => [
                    'ISO 9001:2015 | Quality management system',
                    'ISO 14001:2015 | Environmental management system',
                    'ISO 45001:2018 | Occupational health & safety management system',
                    'Achilles Sustainability Rating | 5 stars, sustainability score 81 (2026)',
                ],
                'iso_image1' => $this->profile('cert-iso-9001-achilles'),
                'iso_image2' => $this->profile('cert-iso-14001-45001'),
                'qa_text' => 'AURCO implements an ISO 9001:2015-aligned quality system to ensure all engineering, procurement, construction, and industrial services consistently meet client and regulatory requirements.',
                'qa_points' => [
                    'Quality policies and objectives',
                    'Skilled personnel and adequate resources',
                    'Documented procedures and quality plans',
                    'Process monitoring and continual improvement',
                    'Customer-focused quality, safety, time, and cost performance',
                ],
                'qc_points' => [
                    'Material and workmanship inspections',
                    'Inspection & Test Plans (ITPs)',
                    'Compliance verification',
                    'Inspection and test documentation',
                    'Non-conformance control',
                    'Final project verification before handover',
                ],
                'qa_culture' => [
                    'Quality starts with us and ends with customer satisfaction',
                    'We follow standard processes and do it right, first time',
                    'We speak up, correct issues, and learn continuously',
                    'We document, measure, and improve every day',
                    'We take pride in delivering excellence in every project',
                ],
                'sustainability_title' => 'Sustainability & the UN SDGs',
                'sustainability_text' => 'At AURCO, sustainability is at the core of our business. We align our strategies and operations with the United Nations Sustainable Development Goals (SDGs) to create lasting value for our people, communities, and the environment — focusing on the goals where we can make the greatest impact.',
                'sdgs' => [
                    '3 | Good Health and Well-Being | Prioritizing safety and a healthy workplace',
                    '4 | Quality Education | Supporting learning, training, and skill development',
                    '8 | Decent Work and Economic Growth | Creating opportunities and driving inclusive growth',
                    '11 | Sustainable Cities and Communities | Contributing to resilient and sustainable infrastructure',
                    '12 | Responsible Consumption and Production | Promoting efficient use of resources',
                    '13 | Climate Action | Reducing environmental impact and adopting sustainable practices',
                    '17 | Partnerships for the Goals | Collaborating with stakeholders to achieve shared goals',
                ],
                'csr_text' => 'We believe that responsible business extends beyond project delivery. Through our Corporate Social Responsibility initiatives, we strive to create a positive and lasting impact on our people, communities and the environment while supporting the sustainable development of the Kingdom of Saudi Arabia.',
                'csr_pillars' => [
                    'Our People | Promoting employee wellbeing, development, safety and professional growth.',
                    'Community Engagement | Supporting communities through meaningful social initiatives and responsible corporate participation.',
                    'Environmental Responsibility | Encouraging responsible resource use, waste reduction and environmentally conscious practices.',
                    'Local Value Creation | Supporting Saudi talent, local suppliers and sustainable economic opportunities in line with Vision 2030.',
                ],
                'csr_image' => $this->profile('csr-commitment'),
                'credentials' => [
                    'Local Content Score | 37% | AURCO achieved a 37% Local Content Score, showing our commitment to developing local value through Saudi-based operations, local procurement, employment, and engagement with the Kingdom’s supply chain.',
                    'Saudi Contractors Authority | Registered member | AURCO is a registered member of the Saudi Contractors Authority (SCA), reflecting our commitment to professional standards, industry compliance, and continuous development within the Saudi construction sector.',
                    'D-U-N-S® Registered | D-U-N-S 55-786-0768 | Our D-U-N-S® registration reflects corporate transparency and verified business identity, supporting recognition across global business networks.',
                    'Cybersecurity Compliance | Saudi Aramco SACS-002 | Independently assessed by Grant Thornton Advisory PLLC against the Saudi Aramco Third Party Cybersecurity Standard (SACS-002) and found compliant with the applicable General Requirements.',
                ],
                'local_content_image' => $this->profile('cert-local-content'),
                'sca_image' => $this->profile('cert-sca'),
                'duns_image' => $this->profile('cert-duns'),
                'cyber_image' => $this->profile('cert-cybersecurity'),
                'government' => [
                    'Commercial Registration — Ministry of Commerce (CR 7001603997)',
                    'Balady / Municipality registration',
                    'Saudi Contractors Authority',
                    'Local Content & Government Procurement Authority',
                    'MHRSD / Qiwa registration',
                    'GOSI registration',
                    'ZATCA / VAT registration',
                    'Saudi Council of Engineers membership',
                ],
                'government_image' => $this->profile('cert-government'),
                'digital_text' => 'AURCO is continuously investing in technology and digital innovation to strengthen operational efficiency, improve business visibility and support informed decision-making. The recent implementation of Microsoft Dynamics 365 represents a significant step in integrating and digitalizing key business processes across the organization.',
                'digital_points' => [
                    'Integrated Operations | Connecting key business functions through a unified digital platform.',
                    'Real-Time Visibility | Improved access to financial, operational and project information.',
                    'Data-Driven Decisions | Supporting management with timely insights and improved reporting.',
                    'Process Efficiency | Streamlining workflows, approvals and business processes.',
                    'Scalable Platform | Building a technology foundation to support AURCO’s continued growth.',
                ],
                'recognition_text' => 'Certificates of appreciation, excellence and safety achievement from Saudi Aramco, NEOM, Acciona, Saipem, Samsung Engineering, JGC, Tecnicas Reunidas, GS Engineering & Construction, AECOM and others.',
                'recognition_image1' => $this->profile('recognition-projects'),
                'recognition_image2' => $this->profile('recognition-qhse'),
                'recognition_image3' => $this->profile('recognition-fm'),
            ],
            'pages' => [
                'projects_hero_title' => 'Our Projects',
                'projects_hero_text' => 'Industrial, infrastructure, modular and facility management projects delivered across the Kingdom since 2009.',
                'projects_eyebrow' => 'Featured projects',
                'projects_title' => 'Projects we are proud of',
                'projects_text' => 'Case studies from our work for Saudi Aramco, NEOM, SWCC and leading EPC contractors. See the full track record below.',
                'gallery_hero_title' => 'Gallery',
                'gallery_hero_text' => 'Site photography from our projects, equipment fleet, fabrication shop and facility management teams.',
                'gallery_eyebrow' => 'Photo gallery',
                'gallery_title' => 'A look inside our work',
                'gallery_text' => 'Browse by discipline: construction projects, mechanical works, modular facilities, fencing, steel fabrication, equipment and facility management.',
                'contact_hero_title' => 'Contact Us',
                'contact_hero_text' => 'Tell us about your project and our team will get back to you.',
            ],
        ];

        foreach ($groups as $group => $payload) {
            if (! Setting::query()->where('group', $group)->exists()) {
                Setting::putGroup($group, $payload);
            }
        }
    }

    private function seedProjects(): void
    {
        // [title, category, location, client, year, value, role, scope, image, featured]
        $projects = [
            ['Al Reef Sugar Refinery', 'Industrial', 'Jazan Economic City, Baish', 'Al Reef Sugar Refinery Co.', 'March 2015', 'SAR 241M', 'Main contractor',
                'Execution of all civil and electro-mechanical works, underground utilities, infrastructure, electrical equipment installation, cable laying & termination and erection of all steel structure. 58,325 m³ concrete, 5,955 t reinforcement, 116,651 m² formwork.',
                'al-reef-sugar-refinery', true],
            ['Seawater Reverse Osmosis Desalination Plant SWRO-2', 'Industrial', 'Al Khobar', 'SWCC', 'November 2020', 'SAR 53.2M', 'Subcontractor to Acciona / RTCC JV',
                'Civil & architectural works for 5 buildings: RO electrical, DAF electrical, main electrical, chemical and sludge buildings. 27,465 m³ earthwork, 20,082 m³ concrete, 4,298 t reinforcement.',
                'swro-2-desalination', true],
            ['Hydrogen Fueling Station', 'Industrial', 'Dhahran Techno Valley, Dhahran', 'Air Products Technology Center', 'January 2019', 'SAR 4.5M', 'Main contractor',
                'Execution of all civil and electro-mechanical works, erection of steel structure canopies with aluminum cladding and installation of fuel dispensers.',
                'hydrogen-fueling-station', true],
            ['Design & Build Permanent Warehouse Facility', 'Buildings & Steel', 'NEOM', 'NEOM Company', 'September 2024', 'SAR 16.1M', 'Main contractor',
                'EPC design, fabrication, supply & installation of a 2,000 m² warehouse including civil, electrical, firefighting, fire alarm & HVAC works.',
                'neom-permanent-warehouse', true],
            ['SFSP Main Centre Design & Build', 'Modular & TCF', 'NEOM Bay', 'NEOM Company', 'April 2021', 'SAR 19.4M', 'Main contractor',
                'EPC design & construction of the SFSP Main Centre at NEOM Bay including a helipad, with operation & maintenance services for one and a half years after completion.',
                'sfsp-main-centre-neom', false],
            ['Design & Build NEOM Zero Site Office', 'Modular & TCF', 'NEOM', 'NEOM Company', 'May 2021', null, 'Main contractor',
                'EPC design & construction of the NEOM Zero Site Office with 3 months of mandatory operation & maintenance and a further 3-month O&M extension.',
                'neom-zero-site-office', false],
            ['Fadhili Gas Increment Program — Camp Facility', 'Modular & TCF', 'Fadhili', 'Saudi Aramco', 'March 2025', 'SAR 49.5M', 'Subcontractor to Consolidated Contractors Company (CCC)',
                'EPC construction of a camp facility for 2,843 persons including civil, mechanical, HVAC & electrical works. 29,685 m² modular building area.',
                'fadhili-gas-camp', false],
            ['Fadhili Gas Increment Program Pkg 2', 'Modular & TCF', 'Fadhili', 'Saudi Aramco', 'March 2025', 'SAR 86.5M', 'Subcontractor to GS Construction Arabia',
                'EPC temporary facilities for camp, office & warehouse including civil, mechanical, firefighting & electrical works. 17,220 m² modular building area.',
                'fadhili-gas-pkg-2', false],
            ['Zuluf Onshore Oil Facilities — Camp Phase I & II', 'Modular & TCF', 'Zuluf', 'Saudi Aramco', 'October 2022', null, 'Subcontractor to JGC',
                'EPC design and construction of temporary camp facilities for 1,000 + 2,500 persons with all associated electrical & mechanical works. 22,280 m² modular building area.',
                'zuluf-camp-phase-1-2', false],
            ['100-Employee Office Facilities for Aramco CIPD', 'Modular & TCF', 'Jazan Economic City, Baish', 'Saudi Aramco', 'February 2016', 'SAR 18.6M', 'Main contractor',
                'EPC construction, operation & maintenance of temporary office facilities for 100 employees of the Aramco CIPD department.',
                'cipd-office-facilities', false],
            ['Tanajib Gas Plant Pkg 5 — Security Support Facilities', 'Infrastructure', 'Tanajib', 'Saudi Aramco', 'July 2025', 'SAR 8.71M', 'Main contractor',
                'Supply and application of subbase, base course & asphalt works inside the plant. 87,345 m² asphalting area.',
                'tanajib-gas-plant-pkg-5', false],
            ['Jafurah Upstream Pipelines & Wells Tie-ins', 'Infrastructure', 'Jafurah', 'Saudi Aramco', 'March 2023', null, 'Subcontractor to Saipem',
                'Supply and installation of tie-in pipelines.',
                'jafurah-pipelines-tie-ins', false],
            ['Berri Development Project Package 1', 'Infrastructure', 'Abu Ali', 'Saudi Aramco', 'March 2022', 'SAR 8.49M', 'Subcontractor to Saipem',
                'Road works for Abu Ali Island access roads, Phase III. 42,000 m² asphalting area.',
                'berri-development-pkg-1', false],
            ['Marjan Increment Pkg 10', 'Infrastructure', 'Marjan', 'Saudi Aramco', 'November 2019', null, 'Subcontractor to Saipem',
                'Site preparation including clearing, grubbing, backfilling, compaction and site levelling for the laydown area. 607,772 m² plot, 303,886 m³ earthwork.',
                'marjan-increment-pkg-10', false],
            ['Al Darb Training Center Renovation', 'Buildings & Steel', 'Al Darb', 'Saudi Aramco', 'March 2015', 'SAR 14.3M', 'Main contractor',
                'Repair and renovation of the existing Al Darb Training Center.',
                'al-darb-training-center', false],
            ['Communication Tower Erection', 'Buildings & Steel', 'Zuluf', 'Salam Telecom', 'June 2023', null, 'Main contractor',
                'EPC design, supply and erection of communication towers including civil works.',
                'communication-tower-erection', false],
        ];

        foreach ($projects as $index => [$title, $category, $location, $client, $year, $value, $role, $scope, $image, $featured]) {
            Project::query()->firstOrCreate(['title' => $title], [
                'category' => $category,
                'location' => $location,
                'client' => $client,
                'year' => $year,
                'contract_value' => $value,
                'role' => $role,
                'summary' => $scope,
                'image_path' => $this->profile("project-$image"),
                'sort_order' => $index,
                'is_featured' => $featured,
                'is_published' => true,
            ]);
        }
    }

    private function seedTrackRecord(): void
    {
        // [section, title, client, location, scope, year, value]
        $rows = [
            ['construction', 'Al Reef Sugar Refinery', 'Al Reef Sugar Refinery Co.', 'Jazan Economic City, Baish', 'Civil, electro-mechanical, underground utilities, infrastructure, electrical equipment installation, cable laying & termination and steel structure erection', '2021', 'SAR 241M'],
            ['construction', 'Seawater Reverse Osmosis Plant 2 (SWRO)', 'Acciona', 'Khobar', 'Civil & architectural works for 5 buildings: RO electrical, DAF electrical, main electrical, chemical and sludge buildings', '2020', 'SAR 53.2M'],
            ['construction', 'Hydrogen Fueling Station, Phase 1', 'Air Products Technology Center', 'Dhahran Techno Valley', 'Civil and electro-mechanical works, steel structure canopies with aluminum cladding and fuel dispensers', '2019', 'SAR 4.5M'],
            ['construction', 'Repair & Renovation of Al Darb Training Center', 'Saudi Aramco', 'Al Darb', 'Repair and renovation of the existing Al Darb Training Center', '2015', 'SAR 14.3M'],
            ['construction', 'Shaybah GHS HVAC Rectification', 'Saudi Aramco', 'Shaybah', 'Shaybah GHS HVAC rectification', '2020', 'SAR 17.2M'],
            ['construction', 'Berri Development Project Package 1', 'Saudi Ready Mix', 'Abu Ali', 'Site preparation and civil works for a concrete ready-mix plant', '2021', 'SAR 2.75M'],
            ['construction', 'Initial Power Supply to Jizan Complex', 'National Contracting Company', 'Jazan Economic City, Baish', 'RCC duct bank, sand duct bank and cable pulling in open areas and under substation', '2021', 'SAR 1.4M'],
            ['construction', 'Design & Build Warehouse', 'NEOM Company', 'NEOM', 'Design, fabrication, supply & installation of a 2,000 m² warehouse including civil, electrical, firefighting, fire alarm & HVAC works', '2024', 'SAR 16.1M'],
            ['construction', 'Qurayyah Expansion CCGT IPP Project', 'Tecnicas Reunidas', 'Qurayyah', 'Expansion works for laydown area and IT tower dismantling & installation', '2025', 'SAR 2.42M'],
            ['construction', 'Zuluf Onshore Oil Facilities Project', 'Salam Telecom', 'Zuluf', 'Supply & installation of a 30 m self-support communication tower', '2025', 'SAR 0.45M'],
            ['construction', 'Upgrade SRU Yanbu Refinery Project', 'GS Engineering & Construction', 'Yanbu', 'Concrete draining & NDT works', '2023', 'SAR 1.6M'],
            ['construction', 'LTA2-53 Enhance LPG Piping', 'Saipem', 'Ras Tanura', 'Retaining wall penetration & associated works', '2022', 'SAR 1.7M'],
            ['construction', 'Earthwork for Crude Oil Pipe Installation', 'Saudi Aramco', 'Jazan Economic City, Baish', 'Earthwork and crude oil pipe installation; grading 100 m wide and 5 km long', '2015', 'SAR 11M'],
            ['construction', 'Al Reef Sugar Refinery — Site Preparation', 'Al Reef Sugar Refinery Co.', 'Jazan Economic City, Baish', 'Site preparation for the sugar mill: 154,000 m² area, 1 million m³ backfill', '2017', 'SAR 5.16M'],
            ['construction', 'Berri Development Project Package 1 — Laydown', 'Saipem', 'Abu Ali', 'Site preparation for laydown embankment area', '2021', 'SAR 4.21M'],
            ['construction', 'Tanajib Gas Plant Pkg 5', 'Saudi Aramco', 'Tanajib', 'Supply & application of subbase, base course & asphalt works', '2025', 'SAR 8.71M'],
            ['construction', 'Berri Development Package 1 — Re-asphalting', 'Saipem', 'Abu Ali', 'Re-asphalting of Abu Ali Island access roads, Phase III', '2022', 'SAR 8.49M'],
            ['construction', 'Tanajib Gas Plant — Highway Fence', 'Saudi Aramco', 'Marjan', 'Highway fence around the construction site to prevent animal crossing, 50 km', '2022', 'SAR 5.43M'],
            ['construction', 'Pan Asia Saudi Petrochemical & Chemical Fibre Integrated Project', 'China Harbour Engineering & Construction Co.', 'Jazan Economic City, Baish', 'HCIS Type III & Type IV chain-link fence around the premises', '2019', 'SAR 1.8M'],
            ['construction', 'CRSTAL Ilmenite Smelting Plant Project', 'CRSTAL', 'Jazan Economic City, Baish', 'Supply & installation of HCIS fence Type IV', '2016', 'SAR 8.49M'],
            ['construction', 'Jazan Economic City Fence Repair', 'Saudi Aramco', 'Jazan Economic City, Baish', 'Fence repair and installation of fence and security gate', '2014', 'SAR 10M'],

            ['tcf', 'Construction of Temporary Camp Facilities', 'Consolidated Contractors Company (CCC)', 'Fadhili', 'Camp facility for 2,843 persons including civil, mechanical, HVAC & electrical works', '2025', 'SAR 49.5M'],
            ['tcf', 'Fadhili Gas Increment Program Pkg 2', 'GS Engineering & Construction', 'Fadhili', 'Temporary facilities for camp, office & warehouse including civil, mechanical, firefighting & electrical works', '2024', 'SAR 86.5M'],
            ['tcf', 'Temporary Facilities — Camp Construction, Zuluf Onshore Oil Facilities', 'JGC', 'Zuluf', 'Design and construction of a temporary camp for 1,000 persons with associated electrical & mechanical works', '2022', 'SAR 54.7M'],
            ['tcf', 'Additional Camp Work, Zuluf Onshore Project', 'JGC Arabia Co. Ltd.', 'Zuluf', 'Design and construction of a temporary camp for 2,500 persons with associated electrical & mechanical works', '2024', 'SAR 36.8M'],
            ['tcf', 'TCF Works for Jafurah Upstream Pipelines & Wells Tie-In', 'Saipem', 'Jafurah', 'Site preparation, gates & fencing, civil, mechanical & electrical works for TCF common works', '2022', 'SAR 27.9M'],
            ['tcf', 'SAPMT Camp Expansion, Jafurah Gas Compression Phase I Pkg 31', 'Larsen & Toubro Saudi Company', 'Jafurah', 'Engineering, procurement & construction of SAPMT camp expansion incl. civil, mechanical, HVAC & electrical works', '2024', 'SAR 24.1M'],
            ['tcf', 'Water Handling Debottlenecking Project', 'SSEM', 'Haradh & Hawiyah', 'Temporary office facilities for SSEM and SAPMT, camp for 600 occupants & satellite offices', '2019', 'SAR 23.9M'],
            ['tcf', 'Design, Build & Operate Water Office Complex in OXAGON', 'NEOM Company', 'NEOM', 'Water office complex with complete utilities network and 3 months of operation & maintenance', '2023', 'SAR 23.3M'],
            ['tcf', 'Marjan Increment Gas Treatment Sulfur Recovery, Package 10', 'Saipem', 'Marjan', 'Temporary facilities and common works', '2020', 'SAR 20.9M'],
            ['tcf', 'SFSP Main Centre Design & Build', 'NEOM Company', 'NEOM', 'SFSP main centre at NEOM Bay including helipad, with operation & maintenance', '2021', 'SAR 19.4M'],
            ['tcf', '100-Employee Facilities for Aramco CIPD Department', 'Saudi Aramco', 'Jazan Economic City, Baish', 'Construction, operation & maintenance of temporary office facilities for 100 employees', '2016', 'SAR 18.6M'],
            ['tcf', 'B2 B4 North Arabia Camp Upgrade', 'Saudi Aramco', 'Turaif', 'New gym & recreation building with equipment, sports court & access road', '2025', 'SAR 17.7M'],
            ['tcf', '60-Men Industrial Support Facility (ISF) and 6-Month O&M', 'Saudi Aramco', 'Jazan Economic City, Baish', 'Construction of a 60-men industrial support facility office with 6 months of O&M', '2015', 'SAR 16.9M'],
            ['tcf', 'KSA HMC CRD Project', 'Hyundai Engineering & Construction', 'Jeddah', 'Temporary facilities for office & warehouse including civil, mechanical, firefighting & electrical works', '2024', 'SAR 16.6M'],
            ['tcf', 'Camp Accommodation & Site Office, Jafurah Onshore Oil Facilities', 'DSCO', 'Jafurah', 'Design, supply & construction of camp accommodation & site office facilities', '2022', 'SAR 16.3M'],
            ['tcf', 'TCF Works for Upgrade SRU Yanbu Refinery Project', 'GS Construction Arabia Co. Ltd.', 'Yanbu', 'Engineering, procurement & construction of temporary facilities incl. civil, mechanical, HVAC & electrical works', '2024', 'SAR 11.5M'],

            ['fm', 'NEOM Office Buildings 222 & 223', 'NEOM Company', 'NEOM', 'O&M of office facility including drinking water, diesel, pest control, sewage removal, cleaning, maintenance & pantry services', '2024', 'SAR 5.25M'],
            ['fm', 'Trainee Office Facilities', 'JIGPC', 'Jazan Economic City', 'O&M of trainee office facilities including fuel for generators, water supply & sewage removal', '2023', 'SAR 4.81M'],
            ['fm', 'NEOM Zero Site Office', 'NEOM Company', 'NEOM', 'Operation and maintenance of NEOM Zero Site Office', '2024', 'SAR 2.86M'],
            ['fm', 'Water Office Complex in OXAGON', 'NEOM Company', 'NEOM', 'O&M of office facility including drinking water, diesel, pest control, sewage removal, cleaning, maintenance & pantry services', '2024', 'SAR 4.73M'],
            ['fm', 'Five Office Buildings & Two Training Centers', 'NEOM Company', 'NEOM', 'O&M of office facilities including drinking water, diesel, pest control, sewage removal, cleaning, maintenance & pantry services', '2023', 'SAR 12.62M'],
            ['fm', 'SFSP Main Centre at NEOM Bay including Helipad', 'NEOM Company', 'NEOM', 'Operation and maintenance of the SFSP Main Centre including helipad', '2023', 'SAR 2.34M'],
            ['fm', 'NEOM Zero Site Office', 'NEOM Company', 'NEOM', 'Operation and maintenance of NEOM Zero Site Office', '2022', 'SAR 6.90M'],
            ['fm', 'Satellite Offices at NEOM Bay', 'NEOM Company', 'NEOM', 'Operation and maintenance of 2 satellite offices for 50 occupants', '2021', 'SAR 10.97M'],
            ['fm', 'Consultants Office Phase 1', 'NEOM Company', 'NEOM', 'O&M of office facility for 300 occupants including drinking water, diesel, pest control, sewage removal, cleaning, maintenance & pantry services', '2023', 'SAR 8.65M'],
            ['fm', 'City Infrastructure Projects Division (CIPD) Office', 'Aramco – SSEM', 'Jazan Economic City, Baish', 'O&M of temporary office facilities for 100 employees of the Aramco CIPD department', '2018', 'SAR 12.66M'],

            ['rental', 'Manpower, Equipment & Earthwork Materials — FGIP PKG#4 Module Yard', 'Samsung E&A Arabia Co., Ltd', 'Fadhili', 'Supply of manpower, equipment & earthwork materials', '2026', null],
            ['rental', 'Manpower, Equipment & Earthwork Materials — FGIP PKG#2', 'GS Construction Arabia', 'Fadhili', 'Supply of manpower, equipment & earthwork materials', '2026', null],
            ['rental', 'Access Road Works — LTA 138', 'Snamprogetti Saudi Arabia Co. Ltd.', 'Ras Tanura', 'Design, supply & installation of office & accommodation camps for LTA-156', '2025', null],
            ['rental', 'Equipment Rental — MGS-III Pkg 1 Upgrade Existing BGCS 1, 3 & 5', 'CPECC', 'Dammam', 'Equipment rental service', '2026', null],

            // Contract values for ongoing work are left out on purpose: the profile repeats the TCF table's figures here.
            ['ongoing', 'TCF Site Office Establishment', 'Larsen & Toubro Limited (L&T)', 'Jafurah', 'CMEP works for TCF GCP-03 & GCP-07 and dismantling works', '2026', null],
            ['ongoing', 'Zuluf Water Treatment Facilities Phase II', 'Samsung E&A Arabia Co., Ltd', 'Tanajib', 'Civil work (PKG#A), ZWTF Phase II', '2026', null],
            ['ongoing', 'Site Preparation with Embankment', 'Samsung E&A Arabia Co., Ltd', 'Tanajib', 'Site preparation / embankment work, Zuluf Water Treatment Facilities Phase II', '2026', null],
            ['ongoing', 'Temporary Facility Work', 'Samsung E&A Arabia Co., Ltd', 'Tanajib', 'Procurement & construction of temporary facilities, Zuluf Water Treatment Facilities Phase II', '2026', null],
            ['ongoing', 'Temporary Site Facility Works', 'SGC Arabia Co., Ltd', 'Yanbu', 'Design, procurement & construction of site office facility and warehouse', '2026', null],
            ['ongoing', 'Hajar Expansion CCGT IPP Project — 70330 Building Works', 'OCI Saudi Arabia Ltd / Tecnicas Reunidas', 'Qurayyah', 'Civil, architectural and electro-mechanical works for the Hajar project', '2025', null],
            ['ongoing', 'Temporary Portable Offices', 'Saudi Aramco', 'Jafurah', 'Supply, installation & services of temporary portable offices on a rental basis', '2024', null],
            ['ongoing', 'City Infrastructure Projects Division (CIPD) Office', 'Petrojet', 'Jazan Economic City', 'O&M of temporary office facilities for the Aramco CIPD department, 100 employees', '2022', null],
        ];

        if (TrackRecord::query()->exists()) {
            return;
        }

        foreach ($rows as $index => [$section, $title, $client, $location, $scope, $year, $value]) {
            TrackRecord::query()->create([
                'section' => $section,
                'title' => $title,
                'client' => $client,
                'location' => $location,
                'scope' => $scope,
                'year' => $year,
                'contract_value' => $value,
                'sort_order' => $index,
                'is_published' => true,
            ]);
        }
    }

    private function seedGallery(): void
    {
        $photos = [];

        $projectPhotos = [
            'hydrogen-fueling-station' => 'Hydrogen Fueling Station, Dhahran',
            'swro-2-desalination' => 'SWRO-2 Desalination Plant, Al Khobar',
            'al-reef-sugar-refinery' => 'Al Reef Sugar Refinery, Jazan',
            'neom-permanent-warehouse' => 'Permanent Warehouse Facility, NEOM',
            'sfsp-main-centre-neom' => 'SFSP Main Centre & Helipad, NEOM',
            'neom-zero-site-office' => 'NEOM Zero Site Office',
            'fadhili-gas-camp' => 'Fadhili Gas Increment Program camp',
            'fadhili-gas-pkg-2' => 'Fadhili Gas Increment Program Pkg 2',
            'zuluf-camp-phase-1-2' => 'Zuluf Onshore camp, Phase I & II',
            'cipd-office-facilities' => 'Aramco CIPD office facilities, Jazan',
            'tanajib-gas-plant-pkg-5' => 'Tanajib Gas Plant Pkg 5 asphalt works',
            'jafurah-pipelines-tie-ins' => 'Jafurah pipelines & wells tie-ins',
            'berri-development-pkg-1' => 'Berri Development Pkg 1 road works',
            'marjan-increment-pkg-10' => 'Marjan Increment Pkg 10 site preparation',
            'al-darb-training-center' => 'Al Darb Training Center renovation',
            'communication-tower-erection' => 'Communication tower erection, Zuluf',
        ];
        foreach ($projectPhotos as $slug => $caption) {
            foreach ([1, 2] as $n) {
                $photos[] = ["gallery-$slug-$n", 'Projects', $n === 1 ? $caption : "$caption (2)"];
            }
        }

        $series = [
            'mechanical' => ['Mechanical', [
                '01' => 'Storage tank erection', '02' => 'Storage tank fabrication', '03' => 'Valve and piping installation',
                '04' => 'Buried pipeline tie-in', '05' => 'Hydrostatic testing', '06' => 'Fire water pump and piping',
                '07' => 'Process piping installation', '08' => 'Heat exchanger hydro-jet cleaning', '09' => 'HVAC ductwork installation',
            ]],
            'modular' => ['Modular & TCF', [
                '01' => 'Modular camp, aerial view', '02' => 'Two-storey modular accommodation', '03' => 'Modular office units',
                '04' => 'AURCO site team', '05' => 'Modular unit delivery', '06' => 'Steel frame for a modular building',
                '07' => 'Two-storey modular block at night', '08' => 'Accommodation corridor', '09' => 'Open-plan modular office',
                '10' => 'Modular lounge and kitchenette', '11' => 'Furnished accommodation room',
            ]],
            'fencing' => ['Fencing', [
                '01' => 'Fence post drilling', '02' => 'Chain-link fence installation', '03' => 'Perimeter fence with barbed wire',
                '04' => 'Fence post alignment', '05' => 'Fence mesh installation', '06' => 'Razor wire installation',
                '07' => 'Security fence with razor wire', '09' => 'Temporary plant fencing', '10' => 'Temporary site fencing',
                '11' => 'Solid panel hoarding',
            ]],
            'steel' => ['Steel Fabrication', [
                '01' => 'Pre-engineered warehouse', '02' => 'Warehouse steel frame interior', '03' => 'Warehouse racking',
                '04' => 'Steel roof erection', '05' => 'Steel frame erection', '06' => 'Completed warehouse, NEOM',
                '07' => 'Fabricated steel rings', '08' => 'Lattice tower fabrication', '09' => 'Steel stair fabrication',
                '10' => 'Pipe spool fabrication', '11' => 'Pipe fittings and spools', '12' => 'Structural welding',
            ]],
            'equipment' => ['Equipment', [
                '01' => 'Rough-terrain crane', '02' => 'Mobile crane', '03' => 'Motor grader', '04' => 'Bulldozer',
                '05' => 'Roller compactor', '06' => 'Wheel loaders', '07' => 'Backhoe loaders', '08' => 'Water tanker',
                '09' => 'Wheeled excavator', '10' => 'Crawler excavator', '11' => 'Dump trucks', '12' => 'Skid steer loaders',
                '13' => 'Low-bed trailer', '14' => 'Forklifts', '15' => 'Staff bus', '16' => 'Boom truck',
            ]],
            'fm' => ['Facility Management', [
                '01' => 'Store keeping', '02' => 'Cleaning services', '03' => 'Generator maintenance', '04' => 'Drain cleaning',
                '05' => 'Furniture cleaning', '06' => 'Site inspection', '07' => 'Guard house cleaning', '08' => 'Office maintenance',
                '09' => 'Electrical panel maintenance', '10' => 'Washroom cleaning', '11' => 'Rooftop AC maintenance',
                '12' => 'Fire extinguisher inspection', '13' => 'Catering service', '14' => 'Meal service', '15' => 'Kitchen operations',
            ]],
        ];
        foreach ($series as $prefix => [$category, $items]) {
            foreach ($items as $n => $caption) {
                $photos[] = ["gallery-$prefix-$n", $category, $caption];
            }
        }

        foreach ($photos as $index => [$image, $category, $caption]) {
            $path = $this->profile($image);

            if (! $path) {
                continue;
            }

            GalleryImage::query()->firstOrCreate(['caption' => $caption], [
                'category' => $category,
                'image_path' => $path,
                'sort_order' => $index,
                'is_published' => true,
            ]);
        }
    }

    private function seedServices(): void
    {
        $services = [
            ['Projects & Construction', 'service-projects',
                'End-to-end construction solutions delivered with precision and commitment — from site development and early works to industrial plants, infrastructure and mechanical services.',
                [
                    'Site Development & Early Works: Earthworks; Site preparation; Soil improvement & piling works; Dewatering; Laydown areas; Evaporation ponds; Sewage treatment plants (STP)',
                    'Industrial & Civil Construction: Industrial buildings; Plant infrastructure; Refineries; Processing plants; Substations; Desalination & water treatment facilities; Waste management facilities',
                    'Infrastructure, Roads & Security: Roadworks; Subgrade, subbase & base course; Asphalt paving; Road markings & signage; Curbs, guardrails & lighting; Security & perimeter fencing; HCIS / MOT / Aramco-compliant fencing',
                    'Mechanical, Piping & Plant Services: Plant equipment erection; Piping & structural welding; Cross-country pipelines; HVAC systems; Firefighting systems; Steel storage tanks; Bolt torquing & tensioning; Blasting, painting & coating',
                ]],
            ['Rental Equipment', 'service-rental',
                'AURCO’s Equipment Rental service gives clients access to a diverse, well-maintained fleet, from heavy machinery to specialized tools, offering flexibility, cost-effectiveness and streamlined project timelines, backed by maintenance and technical support.',
                [
                    'Lifting Equipment: Hydraulic crane; Crawler crane; Boom truck; Forklift; Articulated & telescopic man lift; Mobile crane; Tower crane',
                    'Transportation: Flat bed trailer; Hydraulic low bed trailer; 55 / 33 / 15 seated buses; Pick-ups; SUVs & sedans',
                    'Earth Moving Equipment: Bulldozer; Grader; Wheel loaders; Excavator; Rock breaker; Dump truck; Backhoe; Roller compactor',
                    'Power & Portable Machinery: Air compressor; Tower lights; Welding machine; Power generator',
                ]],
            ['Facility Management', 'service-fm',
                'Integrated facility management covering hard and soft FM, maintenance, housekeeping, janitorial, landscaping, waste management, pest control, security, helpdesk and hospitality support — for offices, camps and industrial facilities, with a focus on safety, continuity, hygiene and operational excellence.',
                [
                    'Hard Services: Power management system (PMS); Building management system (BMS); Elevators and escalators; Access doors & gate barriers; Generators O&M; Green energy solar systems; Mechanical & plumbing systems; HVAC systems; Sewage & water treatment plants; Fire safety & security systems; Fire alarm & firefighting systems; Cladding & carpentry; Telecom & mobile tower O&M; RO plant O&M; Swimming pool O&M',
                    'Soft Services: Housekeeping & janitorial; Landscape & gardening; Waste disposal management; Pest control; Security services; Office management; Helpdesk & call center; Reception & hospitality; Car washing; Irrigation & plantation; Façade cleaning; Deep cleaning',
                    'Catering Services: Events, seminars and community gatherings; Corporate offices; Large-scale construction sites; Remote workforce camps; Temporary site offices',
                ]],
            ['Steel Fabrication & Modular Solutions', 'service-steel-modular',
                'Precision fabrication and erection of structural steel, plus modular and temporary facilities — camp construction, prefabricated buildings, interior fit-outs, installation and complete site development.',
                [
                    'Steel Fabrication & Structures: PEB buildings & warehouses; Structural steel; Pipe racks & pipe spools; Communication towers; Parking sheds; Stairs & handrails; Cage ladders; Steel shades & shelters; Customized steelwork & embedments',
                    'Modular & Temporary Facilities: Modular buildings; Temporary construction facilities; Site offices; Residential camps; Laydown facilities; Single- & double-storey modular solutions',
                    'Security Fencing: Aramco type fence; HCIS / MOT fence; Customized fence; Plant temporary fencing solutions',
                ]],
        ];

        foreach ($services as $index => [$title, $image, $summary, $points]) {
            Service::query()->firstOrCreate(['title' => $title], [
                'summary' => $summary,
                'points' => $points,
                'image_path' => $this->profile($image),
                'sort_order' => $index,
                'is_published' => true,
            ]);
        }
    }

    /** Quotes are taken from letters and certificates reproduced in the company profile. */
    private function seedTestimonials(): void
    {
        $quotes = [
            ['Al Reef Sugar Refinery Co.', 'Letter of commendation, December 2020',
                'AURCO team has the capability to work with different suppliers and contractors and supported Al Reef Sugar Refinery Project construction. Al-Reef Sugar appreciates AURCO Safety and Quality team achieving a milestone of more than 2 Million Man-hours without LTI.'],
            ['Saudi Aramco', 'Certificate of Excellence',
                'This is to acknowledge the outstanding performance and valued contribution of Attiq Ur Rehman Contracting Establishment towards the success of the project. Their dedication, professionalism and commitment to delivering safe, high-quality work are sincerely appreciated.'],
            ['GS Construction Arabia', 'Fadhili Gas Increment Program PKG #2, June 2025',
                'In recognition for the outstanding work performance and dedication in timely completion of the project, implementing our Quality & Safety Management System and positively promoting High Standard of Safety Culture.'],
        ];

        foreach ($quotes as $index => [$name, $role, $quote]) {
            Testimonial::query()->firstOrCreate(['name' => $name], [
                'role' => $role,
                'quote' => $quote,
                'rating' => 0,
                'avatar_path' => null,
                'sort_order' => $index,
                'is_published' => true,
            ]);
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['What services does AURCO provide?', 'We operate three business lines: Projects & Construction (site development, industrial & civil construction, roads, mechanical and piping works), Rental Equipment (a fleet of 200+ units) and Facility Management (hard and soft FM plus catering). These are supported by in-house steel fabrication, modular facilities and security fencing.'],
            ['Which industries and clients do you work with?', 'We serve Oil & Gas, Petrochemical, Power & Utilities, Infrastructure, Industrial, Commercial and Government sectors. Clients include Saudi Aramco, NEOM, SWCC, SABIC, Saipem, Samsung E&A, JGC, Consolidated Contractors Company and many more.'],
            ['Where in Saudi Arabia do you operate?', 'Our head office is in Al Khobar, and we work across the Kingdom including Jubail, Riyadh, Yanbu, Jazan, Jafurah, NEOM and Turaif.'],
            ['What certifications does AURCO hold?', 'AURCO is certified to ISO 9001:2015, ISO 14001:2015 and ISO 45001:2018, holds a 5-star Achilles sustainability rating, is compliant with the Saudi Aramco SACS-002 cybersecurity standard, and is a registered member of the Saudi Contractors Authority.'],
            ['Can we rent equipment for our own project?', 'Yes. Our rental fleet covers lifting, earthmoving, transportation and power equipment, backed by maintenance and technical support. We also run framework agreements that supply manpower, equipment and earthwork materials together.'],
            ['How do I request a quotation?', 'Send us your requirements through the contact form, call +966 13 894 4304 or email info@aurcontracting.com. Our team will arrange a site visit if needed and share a detailed proposal.'],
        ];

        foreach ($faqs as $index => [$question, $answer]) {
            Faq::query()->firstOrCreate(['question' => $question], [
                'answer' => $answer,
                'sort_order' => $index,
                'is_published' => true,
            ]);
        }
    }

    private function seedPosts(): void
    {
        $posts = [
            ['AURCO achieves Saudi Aramco SACS-002 cybersecurity compliance and ISO recertification',
                'An independent assessment by Grant Thornton confirmed compliance with the Saudi Aramco Third Party Cybersecurity Standard, alongside our ISO 9001, 14001 and 45001 certificates.',
                '/credentials#certifications', 'post-iso-sacs', '2026-02-08'],
            ['10 million safe man-hours and zero lost-time incidents',
                'From the Al Reef Sugar Refinery to the SWRO-2 desalination plant and NEOM, our HSE culture has delivered over 10 million safe man-hours with zero LTI.',
                '/credentials#hse', 'post-safe-man-hours', null],
            ['Digitalizing operations with Microsoft Dynamics 365',
                'Our Dynamics 365 rollout connects finance, operations and projects on one platform, while a 37% Local Content Score reflects our investment in the Kingdom.',
                '/credentials#digital', 'business-projects', null],
        ];

        foreach ($posts as $index => [$title, $excerpt, $link, $image, $date]) {
            Post::query()->firstOrCreate(['title' => $title], [
                'excerpt' => $excerpt,
                'link' => $link,
                'image_path' => $this->profile($image),
                'sort_order' => $index,
                'is_published' => true,
                'published_at' => $date,
            ]);
        }
    }
}
