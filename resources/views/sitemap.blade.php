{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($pages as $path)
@php $base = \App\Support\PageMeta::url($path); @endphp
@foreach (['en', 'ar'] as $lang)
    <url>
        <loc>{{ $lang === 'en' ? $base : $base.'?lang=ar' }}</loc>
        <xhtml:link rel="alternate" hreflang="en" href="{{ $base }}"/>
        <xhtml:link rel="alternate" hreflang="ar" href="{{ $base }}?lang=ar"/>
        <changefreq>monthly</changefreq>
        <priority>{{ $path === '/' ? '1.0' : '0.8' }}</priority>
    </url>
@endforeach
@endforeach
</urlset>
