@extends('layouts.admin')
@php $editing = $service->exists; @endphp
@section('title', $editing ? 'Edit service' : 'Add service')
@section('heading', $editing ? 'Edit service' : 'Add service')

@section('content')
    <form method="POST" enctype="multipart/form-data" class="space-y-6"
          action="{{ $editing ? route('admin.services.update', $service) : route('admin.services.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-card title="Service details">
            <div class="grid gap-5 md:grid-cols-2">
                <x-form.input name="title" label="Title" :value="$service->title" required />
                <x-form.input name="sort_order" type="number" label="Sort order" :value="$service->sort_order ?? 0" />
                <x-form.textarea name="summary" label="Summary" :value="$service->summary" wrapper="md:col-span-2"
                                 help="Shown on the Services page." />
                <x-form.textarea name="points" label="Capabilities" rows="8" wrapper="md:col-span-2"
                                 :value="implode(PHP_EOL, (array) $service->points)"
                                 help="One per line. Use “Group: item; item; item” to show a titled group, e.g. Lifting: Mobile crane; Forklift. Commas also work when no item contains a comma." />
                <x-form.image name="image" label="Photo" :current="$service->image_url" :required="! $editing" />
                <x-form.checkbox name="is_published" label="Published" :checked="$service->exists ? $service->is_published : true" />
            </div>
        </x-card>

        <div class="flex items-center gap-4">
            <x-submit>{{ $editing ? 'Save changes' : 'Create service' }}</x-submit>
            <a href="{{ route('admin.services.index') }}" class="text-sm text-slate-500 hover:text-navy">Cancel</a>
        </div>
    </form>
@endsection
