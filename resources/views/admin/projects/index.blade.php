@extends('layouts.admin')
@section('title', 'Projects')
@section('heading', 'Projects')
@section('subheading', 'Shown on the home page (featured) and the Projects page.')

@section('content')
    <div class="flex justify-end">
        <x-primary-link :href="route('admin.projects.create')">Add project</x-primary-link>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="pb-3">Project</th>
                        <th class="pb-3">Category</th>
                        <th class="pb-3">Location</th>
                        <th class="pb-3">Order</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($projects as $project)
                        <tr>
                            <td class="py-3">
                                <div class="flex items-center gap-3">
                                    @if ($project->image_url)
                                        <img src="{{ $project->image_url }}" alt="" class="h-12 w-16 rounded-lg object-cover">
                                    @endif
                                    <div>
                                        <p class="font-medium text-navy">{{ $project->title }}</p>
                                        @if ($project->client || $project->year)
                                            <p class="text-xs text-slate-500">{{ collect([$project->client, $project->year])->filter()->implode(' · ') }}</p>
                                        @endif
                                        @if ($project->is_featured)
                                            <p class="text-xs text-brand">Featured on home page</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-slate-600">{{ $project->category ?: '—' }}</td>
                            <td class="py-3 text-slate-600">{{ $project->location ?: '—' }}</td>
                            <td class="py-3 text-slate-600">{{ $project->sort_order }}</td>
                            <td class="py-3"><x-badge :on="$project->is_published" /></td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="mr-3 text-sm font-medium text-navy hover:text-brand">Edit</a>
                                <x-delete-button :action="route('admin.projects.destroy', $project)" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-slate-500">No projects yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $projects->links() }}</div>
    </x-card>
@endsection
