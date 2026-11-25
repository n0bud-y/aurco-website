# AURCO Contracting — website and CRM

One Laravel application. The public website is a React 19 app rendered through
Inertia, the CRM lives at `/admin`, and everything the CRM manages is handed to
React as page props. No second server, no API keys, no CORS.

## Requirements

- PHP 8.2+ and Composer
- Node 20.19+ (or 22.12+) to build the front-end assets
- No database server needed — SQLite by default

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed      # schema + your current site content and photos
php artisan storage:link        # makes uploaded images reachable
npm run build                   # or `npm run dev` for hot reload while developing
php artisan serve
```

| | |
| --- | --- |
| Website | http://localhost:8000 |
| CRM | http://localhost:8000/admin |
| Login | `admin@aurcontracting.com` / `password` (set in `.env`) |

While developing the front end, run `npm run dev` in one terminal and
`php artisan serve` in another — edits to React files refresh instantly.

## How the pieces fit

```
routes/web.php
  /  /about  /services  /projects  /credentials  /gallery  /contact  → SiteController → Inertia::render(...)
  /admin/*                                     → Blade CRM screens
  POST /contact                                → stores a message, shown in the CRM

app/Http/Middleware/HandleInertiaRequests.php  shares `content` with every page
app/Support/SiteContent.php                    builds that payload from the database

resources/js/
  app.jsx              Inertia entry, fonts, smooth scroll
  Pages/               Home, About, Services, Projects, Credentials, Gallery, Contact, NotFound
  Layouts/SiteLayout   persistent shell: header, footer, motion, cursor
  components/          every section of the site
  motion/              GSAP + Lenis: loader, reveals, parallax, page curtain
  content/useContent   `useContent()` → the shared content props

resources/views/
  app.blade.php        the Inertia root view
  admin/               CRM screens (Blade + Tailwind from the CDN)
```

Because the layout is persistent, moving between pages keeps the smooth scroller
and motion alive — Inertia swaps only the page component, and the curtain animation
covers the change.

## What the CRM manages

| Screen | Controls |
| --- | --- |
| Projects | Case studies: photo, category, location, client, year, contract value, AURCO role, scope. Tick "Show on the home page" for the four home cards |
| Track record | The completed / ongoing project tables on the Projects page (construction, TCF, FM, rental, ongoing) |
| Gallery | Gallery photos, uploaded several at a time, with captions and categories |
| Services | The four cards in the services row |
| Testimonials | Names, roles, quotes, ratings, photos |
| FAQs | Questions and answers |
| Blog posts | Titles, cover photos, dates, links |
| Site content | All remaining text and images: hero, about, why-choose-us, call to action, contact details, footer links, inner page banners |
| Arabic text | The Arabic version of every piece of content above. Empty entries fall back to English |
| Messages | Everything sent through the contact form |

Categories are free text: whatever you type becomes a filter button on the site.

### Adding a content field

`config/content.php` defines every field on the "Site content" screen — each group
is a tab, each entry an input (`text`, `textarea`, `lines`, `pairs`, `image`,
`select`). Add a field there, then read it in `app/Support/SiteContent.php` so React
receives it. No migration needed: content is stored in a JSON column.

## Arabic version

The ENG / AR switch in the header changes the whole site to Arabic, right to left, using
IBM Plex Sans Arabic. Any page also opens in Arabic with `?lang=ar` (for example `/about?lang=ar`).

- **CRM content** is translated on the server by `app/Support/Translator.php`, using the
  `translations` table. Edit it under **Arabic text** in the CRM. The English text is the key, so
  when English changes, its Arabic shows up as missing until it is updated.
- **Fixed interface text** (buttons, form labels, headings written in the components) lives in
  `resources/js/lang/ar.js` and is read through `useT()` from `resources/js/lib/i18n.js`.
- **Right-to-left fixes** are in `resources/js/styles/rtl.css`.
- The shipped Arabic is in `database/seeders/data/translations-ar.json` and is loaded by
  `TranslationSeeder` (it never overwrites Arabic already edited in the CRM).

## Notes

- Uploads live in `storage/app/public` and are served from `/storage/...`, which is why
  `php artisan storage:link` matters. Image URLs in the payload are absolute, built from `APP_URL`.
- The CRM uses the Tailwind CDN, so it needs no build step. The public site is built by Vite.
- `php artisan migrate:fresh --seed` resets everything back to the shipped content.
- Motion is documented in `resources/js/motion/` — every animation is skipped for visitors
  who prefer reduced motion.
- For production: set `APP_ENV=production`, `APP_DEBUG=false` and a real `APP_URL`, run
  `npm run build` and `php artisan migrate --force`, then point your web server at `public/`.
  Switch `DB_CONNECTION` to MySQL in `.env` if you prefer it to SQLite.
