@props(['title' => null, 'description' => null])
<section {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-6 shadow-sm']) }}>
    @if ($title)
        <h2 class="text-base font-semibold text-navy">{{ $title }}</h2>
        @if ($description)<p class="mt-1 text-sm text-slate-500">{{ $description }}</p>@endif
        <div class="mt-5">{{ $slot }}</div>
    @else
        {{ $slot }}
    @endif
</section>
