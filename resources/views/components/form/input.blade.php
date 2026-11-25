@props(['name', 'label' => null, 'value' => null, 'type' => 'text', 'help' => null, 'required' => false, 'wrapper' => ''])
@php $htmlName = str_contains($name, '.') ? preg_replace('/\.([^.]+)/', '[$1]', $name) : $name; @endphp
<x-form.field :name="$name" :label="$label" :help="$help" :wrapper="$wrapper">
    <input type="{{ $type }}" name="{{ $htmlName }}" id="{{ $name }}" value="{{ old($name, $value) }}" @required($required)
           {{ $attributes->merge(['class' => 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand']) }}>
</x-form.field>
