<!doctype html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { brand: { DEFAULT: '#c8161e', dark: '#7c0d12' }, navy: '#131f34' } } }
        }
    </script>
</head>
<body class="h-full text-slate-800">
<div class="flex min-h-full">
    <aside class="hidden w-64 shrink-0 flex-col bg-navy text-white lg:flex">
        <div class="px-6 py-6 text-lg font-bold tracking-tight">AURCO <span class="text-brand">CRM</span></div>
        <nav class="flex-1 space-y-1 px-3 pb-6 text-sm">
            @php
                $links = [
                    ['admin.dashboard', 'Dashboard'],
                    ['admin.projects.index', 'Projects'],
                    ['admin.track-records.index', 'Track record'],
                    ['admin.gallery.index', 'Gallery'],
                    ['admin.services.index', 'Services'],
                    ['admin.testimonials.index', 'Testimonials'],
                    ['admin.faqs.index', 'FAQs'],
                    ['admin.posts.index', 'Blog posts'],
                    ['admin.content.edit', 'Site content'],
                    ['admin.messages.index', 'Messages'],
                ];
            @endphp
            @foreach ($links as [$route, $label])
                @php $active = request()->routeIs(str_replace('.index', '.*', $route)) || request()->routeIs($route); @endphp
                <a href="{{ route($route) }}"
                   class="flex items-center justify-between rounded-lg px-3 py-2 transition {{ $active ? 'bg-brand text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    {{ $label }}
                    @if ($label === 'Messages' && ($unreadCount = \App\Models\Message::whereNull('read_at')->count()))
                        <span class="rounded-full bg-white px-2 text-xs font-semibold text-navy">{{ $unreadCount }}</span>
                    @endif
                </a>
            @endforeach
        </nav>
        <div class="px-6 py-4 text-xs text-slate-400">
            <a class="hover:text-white" href="{{ url('/') }}" target="_blank" rel="noopener">View the website →</a>
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="flex items-center justify-between gap-4 border-b border-slate-200 bg-white px-6 py-4">
            <div>
                <h1 class="text-xl font-semibold text-navy">@yield('heading', 'Dashboard')</h1>
                @hasSection('subheading')<p class="text-sm text-slate-500">@yield('subheading')</p>@endif
            </div>
            <div class="flex items-center gap-4 text-sm">
                <span class="hidden text-slate-500 sm:inline">{{ auth()->user()?->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg border border-slate-200 px-3 py-1.5 font-medium text-slate-600 hover:bg-slate-50">Log out</button>
                </form>
            </div>
        </header>

        <main class="flex-1 space-y-6 p-6">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    Please check the highlighted fields.
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<nav class="flex gap-2 overflow-x-auto bg-navy px-4 py-2 text-xs text-white lg:hidden">
    @foreach ($links as [$route, $label])
        <a class="whitespace-nowrap rounded px-2 py-1 hover:bg-white/10" href="{{ route($route) }}">{{ $label }}</a>
    @endforeach
</nav>
</body>
</html>
