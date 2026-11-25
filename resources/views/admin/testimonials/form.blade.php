@extends('layouts.admin')
@php $editing = $testimonial->exists; @endphp
@section('title', $editing ? 'Edit testimonial' : 'Add testimonial')
@section('heading', $editing ? 'Edit testimonial' : 'Add testimonial')

@section('content')
    <form method="POST" enctype="multipart/form-data" class="space-y-6"
          action="{{ $editing ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-card title="Testimonial">
            <div class="grid gap-5 md:grid-cols-2">
                <x-form.input name="name" label="Name" :value="$testimonial->name" required />
                <x-form.input name="role" label="Role" :value="$testimonial->role" />
                <x-form.textarea name="quote" label="Quote" :value="$testimonial->quote" rows="5" required wrapper="md:col-span-2" />
                <x-form.image name="avatar" label="Photo" :current="$testimonial->avatar_url" />
                <div class="space-y-4">
                    <x-form.select name="rating" label="Rating" :value="(string) ($testimonial->rating ?? 5)"
                                   :options="['5' => '5 stars', '4' => '4 stars', '3' => '3 stars', '2' => '2 stars', '1' => '1 star', '0' => 'No stars (quotes from certificates)']" />
                    <x-form.input name="sort_order" type="number" label="Sort order" :value="$testimonial->sort_order ?? 0" />
                    <x-form.checkbox name="is_published" label="Published" :checked="$testimonial->exists ? $testimonial->is_published : true" />
                </div>
            </div>
        </x-card>

        <div class="flex items-center gap-4">
            <x-submit>{{ $editing ? 'Save changes' : 'Create testimonial' }}</x-submit>
            <a href="{{ route('admin.testimonials.index') }}" class="text-sm text-slate-500 hover:text-navy">Cancel</a>
        </div>
    </form>
@endsection
