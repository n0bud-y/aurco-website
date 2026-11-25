@extends('layouts.admin')
@section('title', 'Testimonials')
@section('heading', 'Testimonials')

@section('content')
    <div class="flex justify-end">
        <x-primary-link :href="route('admin.testimonials.create')">Add testimonial</x-primary-link>
    </div>

    <x-card>
        <div class="divide-y divide-slate-100">
            @forelse ($testimonials as $testimonial)
                <div class="flex flex-wrap items-start gap-4 py-4">
                    @if ($testimonial->avatar_url)
                        <img src="{{ $testimonial->avatar_url }}" alt="" class="h-12 w-12 rounded-full object-cover">
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-navy">{{ $testimonial->name }} <span class="text-sm font-normal text-slate-500">· {{ $testimonial->role }}</span></p>
                        <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $testimonial->quote }}</p>
                        <p class="mt-1 text-xs text-amber-500">{{ str_repeat('★', $testimonial->rating) }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge :on="$testimonial->is_published" />
                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-sm font-medium text-navy hover:text-brand">Edit</a>
                        <x-delete-button :action="route('admin.testimonials.destroy', $testimonial)" />
                    </div>
                </div>
            @empty
                <p class="py-4 text-sm text-slate-500">No testimonials yet.</p>
            @endforelse
        </div>
    </x-card>
@endsection
