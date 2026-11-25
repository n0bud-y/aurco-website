@extends('layouts.admin')
@php $editing = $project->exists; @endphp
@section('title', $editing ? 'Edit project' : 'Add project')
@section('heading', $editing ? 'Edit project' : 'Add project')

@section('content')
    <form method="POST" enctype="multipart/form-data" class="space-y-6"
          action="{{ $editing ? route('admin.projects.update', $project) : route('admin.projects.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-card title="Project details">
            <div class="grid gap-5 md:grid-cols-2">
                <x-form.input name="title" label="Title" :value="$project->title" required wrapper="md:col-span-2" />
                <x-form.input name="category" label="Category" :value="$project->category" help="Used for the filter buttons, e.g. Industrial." />
                <x-form.input name="location" label="Location" :value="$project->location" />
                <x-form.input name="client" label="Client" :value="$project->client" help="e.g. Saudi Aramco" />
                <x-form.input name="year" label="Year" :value="$project->year" help="e.g. March 2025" />
                <x-form.input name="contract_value" label="Contract value" :value="$project->contract_value" help="Optional, e.g. SAR 16.1M" />
                <x-form.input name="role" label="AURCO role" :value="$project->role" help="e.g. Main contractor, or Subcontractor to Saipem" />
                <x-form.textarea name="summary" label="Scope / short description" :value="$project->summary" wrapper="md:col-span-2" />
                <x-form.image name="image" label="Photo" :current="$project->image_url" :required="! $editing" />
                <div class="space-y-4">
                    <x-form.input name="sort_order" type="number" label="Sort order" :value="$project->sort_order ?? 0" help="Lower numbers appear first." />
                    <x-form.checkbox name="is_featured" label="Show on the home page" :checked="$project->is_featured" />
                    <x-form.checkbox name="is_published" label="Published" :checked="$project->is_published" />
                </div>
            </div>
        </x-card>

        <div class="flex items-center gap-4">
            <x-submit>{{ $editing ? 'Save changes' : 'Create project' }}</x-submit>
            <a href="{{ route('admin.projects.index') }}" class="text-sm text-slate-500 hover:text-navy">Cancel</a>
        </div>
    </form>
@endsection
