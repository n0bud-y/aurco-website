<button {{ $attributes->merge(['class' => 'inline-flex items-center rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-dark']) }}>
    {{ $slot }}
</button>
