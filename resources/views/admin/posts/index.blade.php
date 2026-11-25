@extends('layouts.admin')
@section('title', 'Blog posts')
@section('heading', 'Blog posts')

@section('content')
    <div class="flex justify-end">
        <x-primary-link :href="route('admin.posts.create')">Add post</x-primary-link>
    </div>

    <x-card>
        <div class="divide-y divide-slate-100">
            @forelse ($posts as $post)
                <div class="flex flex-wrap items-center gap-4 py-4">
                    @if ($post->image_url)
                        <img src="{{ $post->image_url }}" alt="" class="h-14 w-20 rounded-lg object-cover">
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-navy">{{ $post->title }}</p>
                        <p class="text-xs text-slate-500">{{ $post->published_at?->format('d M Y') ?: 'No date' }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge :on="$post->is_published" />
                        <a href="{{ route('admin.posts.edit', $post) }}" class="text-sm font-medium text-navy hover:text-brand">Edit</a>
                        <x-delete-button :action="route('admin.posts.destroy', $post)" />
                    </div>
                </div>
            @empty
                <p class="py-4 text-sm text-slate-500">No posts yet.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $posts->links() }}</div>
    </x-card>
@endsection
