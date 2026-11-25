@props(['href'])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark']) }}>
    {{ $slot }}
</a>
