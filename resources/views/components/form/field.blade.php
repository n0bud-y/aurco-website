@props(['name', 'label' => null, 'help' => null, 'wrapper' => ''])
<div class="space-y-1.5 {{ $wrapper }}">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">{{ $label }}</label>
    @endif
    {{ $slot }}
    @if ($help)<p class="text-xs text-slate-500">{{ $help }}</p>@endif
    @error($name)<p class="text-xs text-red-600">{{ $message }}</p>@enderror
</div>
