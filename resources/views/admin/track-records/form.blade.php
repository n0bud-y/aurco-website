@extends('layouts.admin')
@php $editing = $record->exists; @endphp
@section('title', $editing ? 'Edit record' : 'Add record')
@section('heading', $editing ? 'Edit record' : 'Add record')

@section('content')
    <form method="POST" class="space-y-6"
          action="{{ $editing ? route('admin.track-records.update', $record) : route('admin.track-records.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-card title="Record details">
            <div class="grid gap-5 md:grid-cols-2">
                <x-form.select name="section" label="Table" :value="$record->section" :options="$sections" wrapper="md:col-span-2" />
                <x-form.input name="title" label="Project title" :value="$record->title" required wrapper="md:col-span-2" />
                <x-form.input name="client" label="Client" :value="$record->client" />
                <x-form.input name="location" label="Location" :value="$record->location" />
                <x-form.textarea name="scope" label="Scope description" :value="$record->scope" rows="3" wrapper="md:col-span-2" />
                <x-form.input name="year" label="Execution year" :value="$record->year" />
                <x-form.input name="contract_value" label="Contract value" :value="$record->contract_value" help="Optional, e.g. SAR 49.5M. Leave empty to hide." />
                <x-form.input name="sort_order" type="number" label="Sort order" :value="$record->sort_order ?? 0" />
                <x-form.checkbox name="is_published" label="Published" :checked="$record->exists ? $record->is_published : true" />
            </div>
        </x-card>

        <div class="flex items-center gap-4">
            <x-submit>{{ $editing ? 'Save changes' : 'Add record' }}</x-submit>
            <a href="{{ route('admin.track-records.index', ['section' => $record->section]) }}" class="text-sm text-slate-500 hover:text-navy">Cancel</a>
        </div>
    </form>
@endsection
