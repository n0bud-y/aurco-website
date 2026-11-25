@extends('layouts.admin')
@section('title', 'Gallery')
@section('heading', 'Gallery')
@section('subheading', 'Photos shown on the Gallery page.')

@section('content')
    <div class="flex justify-end">
        <x-primary-link :href="route('admin.gallery.create')">Add photos</x-primary-link>
    </div>

    <x-card>
        @if ($images->isEmpty())
            <p class="text-sm text-slate-500">No photos yet.</p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($images as $image)
                    <div class="overflow-hidden rounded-xl border border-slate-200">
                        <img src="{{ $image->image_url }}" alt="" class="h-40 w-full object-cover">
                        <div class="space-y-2 p-3">
                            <p class="truncate text-sm font-medium text-navy">{{ $image->caption ?: 'Untitled' }}</p>
                            <p class="text-xs text-slate-500">{{ $image->category ?: 'No category' }} · #{{ $image->sort_order }}</p>
                            <div class="flex items-center justify-between">
                                <x-badge :on="$image->is_published" />
                                <span>
                                    <a href="{{ route('admin.gallery.edit', $image) }}" class="mr-3 text-sm font-medium text-navy hover:text-brand">Edit</a>
                                    <x-delete-button :action="route('admin.gallery.destroy', $image)" confirm="Delete this photo?" />
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $images->links() }}</div>
        @endif
    </x-card>
@endsection
