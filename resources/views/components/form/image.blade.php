@props(['name', 'label' => null, 'current' => null, 'help' => null, 'required' => false, 'removeName' => null, 'wrapper' => ''])
@php $htmlName = str_contains($name, '.') ? preg_replace('/\.([^.]+)/', '[$1]', $name) : $name; @endphp
<x-form.field :name="$name" :label="$label" :help="$help" :wrapper="$wrapper">
    <div class="flex flex-wrap items-start gap-4">
        @if ($current)
            <img src="{{ $current }}" alt="" class="h-20 w-28 rounded-lg border border-slate-200 object-cover">
        @endif
        <div class="space-y-2">
            <input type="file" name="{{ $htmlName }}" id="{{ $name }}" accept="image/*" @required($required)
                   class="block text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium hover:file:bg-slate-200">
            @if ($current && $removeName)
                <label class="flex items-center gap-2 text-xs text-slate-500">
                    <input type="checkbox" name="{{ $removeName }}" value="1" class="rounded border-slate-300"> Remove this image
                </label>
            @endif
        </div>
    </div>
</x-form.field>
