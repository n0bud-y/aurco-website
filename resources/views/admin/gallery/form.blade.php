@extends('layouts.admin')
@php $editing = $image->exists; @endphp
@section('title', $editing ? 'Edit photo' : 'Add photos')
@section('heading', $editing ? 'Edit photo' : 'Add photos')

@section('content')
    <form method="POST" enctype="multipart/form-data" class="space-y-6"
          action="{{ $editing ? route('admin.gallery.update', $image) : route('admin.gallery.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-card title="Photo details">
            <div class="grid gap-5 md:grid-cols-2">
                <x-form.input name="caption" label="Caption" :value="$image->caption" />
                <x-form.input name="category" label="Category" :value="$image->category" help="Used for the filter buttons, e.g. Sites." />

                @if ($editing)
                    <x-form.image name="image" label="Photo" :current="$image->image_url" />
                @else
                    <x-form.field name="images" label="Photos" help="You can select several files at once.">
                        <input type="file" name="images[]" id="images" accept="image/*" multiple required
                               class="block text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium hover:file:bg-slate-200">
                    </x-form.field>
                @endif

                <div class="space-y-4">
                    <x-form.input name="sort_order" type="number" label="Sort order" :value="$image->sort_order ?? 0" />
                    <x-form.checkbox name="is_published" label="Published" :checked="$image->exists ? $image->is_published : true" />
                </div>
            </div>
        </x-card>

        <div class="flex items-center gap-4">
            <x-submit>{{ $editing ? 'Save changes' : 'Upload photos' }}</x-submit>
            <a href="{{ route('admin.gallery.index') }}" class="text-sm text-slate-500 hover:text-navy">Cancel</a>
        </div>
    </form>
@endsection
