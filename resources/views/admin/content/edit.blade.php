@extends('layouts.admin')
@section('title', 'Site content')
@section('heading', 'Site content')
@section('subheading', $definition['description'] ?? 'Text and images used across the website.')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[240px_minmax(0,1fr)]">
        <nav class="flex gap-2 overflow-x-auto rounded-xl border border-slate-200 bg-white p-2 lg:flex-col lg:overflow-visible">
            @foreach ($groups as $key => $item)
                <a href="{{ route('admin.content.edit', $key) }}"
                   class="whitespace-nowrap rounded-lg px-3 py-2 text-sm {{ $key === $group ? 'bg-navy font-medium text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <form method="POST" enctype="multipart/form-data" class="space-y-6" action="{{ route('admin.content.update', $group) }}">
            @csrf
            @method('PUT')

            <x-card :title="$definition['label']">
                <div class="grid gap-5">
                    @foreach ($definition['fields'] as $key => $field)
                        @php $value = $values[$key] ?? null; @endphp
                        @switch($field['type'])
                            @case('textarea')
                                <x-form.textarea :name="'fields.'.$key" :label="$field['label']" :value="$value" :help="$field['help'] ?? null" />
                                @break
                            @case('lines')
                                <x-form.textarea :name="'fields.'.$key" :label="$field['label']"
                                                 :value="implode(PHP_EOL, (array) $value)"
                                                 :help="$field['help'] ?? 'One item per line.'" />
                                @break
                            @case('pairs')
                                <x-form.textarea :name="'fields.'.$key" :label="$field['label']"
                                                 :value="collect((array) $value)->map(fn ($pair) => ($pair['label'] ?? '').' | '.($pair['href'] ?? ''))->implode(PHP_EOL)"
                                                 :help="$field['help'] ?? 'One per line, written as: Label | /url'" />
                                @break
                            @case('image')
                                <x-form.image :name="'fields.'.$key" :label="$field['label']"
                                              :current="\App\Support\Media::url($value)"
                                              :remove-name="'remove['.$key.']'"
                                              :help="$field['help'] ?? null" />
                                @break
                            @case('select')
                                <x-form.select :name="'fields.'.$key" :label="$field['label']" :value="$value" :options="$field['options'] ?? []" />
                                @break
                            @default
                                <x-form.input :name="'fields.'.$key" :label="$field['label']" :value="$value" :help="$field['help'] ?? null" />
                        @endswitch
                    @endforeach
                </div>
            </x-card>

            <x-submit>Save content</x-submit>
        </form>
    </div>
@endsection
