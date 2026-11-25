@extends('layouts.admin')
@section('title', 'Track record')
@section('heading', 'Track record')
@section('subheading', 'The project tables shown under “Track record” on the Projects page.')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <nav class="flex flex-wrap gap-2">
            @foreach ($sections as $key => $label)
                <a href="{{ route('admin.track-records.index', ['section' => $key]) }}"
                   class="rounded-lg px-3 py-2 text-sm {{ $key === $section ? 'bg-navy font-medium text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }} <span class="opacity-60">({{ $counts[$key] ?? 0 }})</span>
                </a>
            @endforeach
        </nav>
        <x-primary-link :href="route('admin.track-records.create', ['section' => $section])">Add record</x-primary-link>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="pb-3">Project</th>
                        <th class="pb-3">Client</th>
                        <th class="pb-3">Location</th>
                        <th class="pb-3">Year</th>
                        <th class="pb-3">Value</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($records as $record)
                        <tr>
                            <td class="py-3 pr-4">
                                <p class="font-medium text-navy">{{ $record->title }}</p>
                                @if ($record->scope)<p class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($record->scope, 110) }}</p>@endif
                            </td>
                            <td class="py-3 pr-4 text-slate-600">{{ $record->client ?: '—' }}</td>
                            <td class="py-3 pr-4 text-slate-600">{{ $record->location ?: '—' }}</td>
                            <td class="py-3 pr-4 text-slate-600">{{ $record->year ?: '—' }}</td>
                            <td class="py-3 pr-4 text-slate-600 whitespace-nowrap">{{ $record->contract_value ?: '—' }}</td>
                            <td class="py-3"><x-badge :on="$record->is_published" /></td>
                            <td class="py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.track-records.edit', $record) }}" class="mr-3 text-sm font-medium text-navy hover:text-brand">Edit</a>
                                <x-delete-button :action="route('admin.track-records.destroy', $record)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-slate-500">No records in this table yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
@endsection
