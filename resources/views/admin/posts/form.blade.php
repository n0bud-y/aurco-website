@extends('layouts.admin')
@php $editing = $post->exists; @endphp
@section('title', $editing ? 'Edit post' : 'Add post')
@section('heading', $editing ? 'Edit post' : 'Add post')

@section('content')
    <form method="POST" enctype="multipart/form-data" class="space-y-6"
          action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-card title="Post">
            <div class="grid gap-5 md:grid-cols-2">
                <x-form.input name="title" label="Title" :value="$post->title" required wrapper="md:col-span-2" />
                <x-form.input name="excerpt" label="Short summary" :value="$post->excerpt" wrapper="md:col-span-2" />
                <x-form.textarea name="body" label="Body" :value="$post->body" rows="8" wrapper="md:col-span-2" />
                <x-form.input name="link" label="External link" :value="$post->link" help="Optional — where the 'Read more' link should go." />
                <x-form.input name="published_at" type="date" label="Date" :value="$post->published_at?->format('Y-m-d')" />
                <x-form.image name="image" label="Cover photo" :current="$post->image_url" :required="! $editing" />
                <div class="space-y-4">
                    <x-form.input name="sort_order" type="number" label="Sort order" :value="$post->sort_order ?? 0" />
                    <x-form.checkbox name="is_published" label="Published" :checked="$post->exists ? $post->is_published : true" />
                </div>
            </div>
        </x-card>

        <div class="flex items-center gap-4">
            <x-submit>{{ $editing ? 'Save changes' : 'Create post' }}</x-submit>
            <a href="{{ route('admin.posts.index') }}" class="text-sm text-slate-500 hover:text-navy">Cancel</a>
        </div>
    </form>
@endsection
