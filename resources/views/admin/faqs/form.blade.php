@extends('layouts.admin')
@php $editing = $faq->exists; @endphp
@section('title', $editing ? 'Edit question' : 'Add question')
@section('heading', $editing ? 'Edit question' : 'Add question')

@section('content')
    <form method="POST" class="space-y-6"
          action="{{ $editing ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-card title="Question">
            <div class="grid gap-5">
                <x-form.input name="question" label="Question" :value="$faq->question" required />
                <x-form.textarea name="answer" label="Answer" :value="$faq->answer" rows="4" required />
                <div class="grid gap-5 md:grid-cols-2">
                    <x-form.input name="sort_order" type="number" label="Sort order" :value="$faq->sort_order ?? 0" />
                    <x-form.checkbox name="is_published" label="Published" :checked="$faq->exists ? $faq->is_published : true" />
                </div>
            </div>
        </x-card>

        <div class="flex items-center gap-4">
            <x-submit>{{ $editing ? 'Save changes' : 'Add question' }}</x-submit>
            <a href="{{ route('admin.faqs.index') }}" class="text-sm text-slate-500 hover:text-navy">Cancel</a>
        </div>
    </form>
@endsection
