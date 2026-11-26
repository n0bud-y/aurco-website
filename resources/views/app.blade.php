<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ \App\Support\Translator::isRtl(app()->getLocale()) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#c8161e">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @isset($meta)
        <title inertia>{{ $meta['title'] }}</title>
        <meta name="description" content="{{ $meta['description'] }}">
        @unless ($meta['indexable'])
            <meta name="robots" content="noindex, nofollow">
        @endunless
        <link rel="canonical" href="{{ $meta['canonical'] }}">
        @foreach ($meta['alternates'] as $lang => $href)
            <link rel="alternate" hreflang="{{ $lang }}" href="{{ $href }}">
        @endforeach
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $meta['site'] }}">
        <meta property="og:title" content="{{ $meta['title'] }}">
        <meta property="og:description" content="{{ $meta['description'] }}">
        <meta property="og:url" content="{{ $meta['canonical'] }}">
        <meta property="og:locale" content="{{ $meta['locale'] }}">
        @if ($meta['image'])
            <meta property="og:image" content="{{ $meta['image'] }}">
            <meta name="twitter:card" content="summary_large_image">
        @endif
    @endisset
    @viteReactRefresh
    @vite(['resources/js/app.jsx'])
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
