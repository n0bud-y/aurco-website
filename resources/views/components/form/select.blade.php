@props(['name', 'label' => null, 'value' => null, 'options' => [], 'help' => null, 'wrapper' => ''])
@php $htmlName = str_contains($name, '.') ? preg_replace('/\.([^.]+)/', '[$1]', $name) : $name; @endphp
<x-form.field :name="$name" :label="$label" :help="$help" :wrapper="$wrapper">
    <select name="{{ $htmlName }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand']) }}>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
</x-form.field>
