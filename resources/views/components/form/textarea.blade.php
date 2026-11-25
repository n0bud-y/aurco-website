@props(['name', 'label' => null, 'value' => null, 'help' => null, 'rows' => 4, 'required' => false, 'wrapper' => ''])
@php $htmlName = str_contains($name, '.') ? preg_replace('/\.([^.]+)/', '[$1]', $name) : $name; @endphp
<x-form.field :name="$name" :label="$label" :help="$help" :wrapper="$wrapper">
    <textarea name="{{ $htmlName }}" id="{{ $name }}" rows="{{ $rows }}" @required($required)
              {{ $attributes->merge(['class' => 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand']) }}>{{ old($name, $value) }}</textarea>
</x-form.field>
