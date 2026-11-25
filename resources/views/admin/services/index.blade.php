@extends('layouts.admin')
@section('title', 'Services')
@section('heading', 'Services')
@section('subheading', 'The four cards in the "Our construction services" row.')

@section('content')
    <div class="flex justify-end">
        <x-primary-link :href="route('admin.services.create')">Add service</x-primary-link>
    </div>

    <x-card>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($services as $service)
                <div class="overflow-hidden rounded-xl border border-slate-200">
                    @if ($service->image_url)
                        <img src="{{ $service->image_url }}" alt="" class="h-36 w-full object-cover">
                    @endif
                    <div class="space-y-2 p-3">
                        <p class="font-medium text-navy">{{ $service->title }}</p>
                        <div class="flex items-center justify-between">
                            <x-badge :on="$service->is_published" />
                            <span>
                                <a href="{{ route('admin.services.edit', $service) }}" class="mr-3 text-sm font-medium text-navy hover:text-brand">Edit</a>
                                <x-delete-button :action="route('admin.services.destroy', $service)" />
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500">No services yet.</p>
            @endforelse
        </div>
    </x-card>
@endsection
