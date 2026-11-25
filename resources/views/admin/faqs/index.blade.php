@extends('layouts.admin')
@section('title', 'FAQs')
@section('heading', 'Frequently asked questions')

@section('content')
    <div class="flex justify-end">
        <x-primary-link :href="route('admin.faqs.create')">Add question</x-primary-link>
    </div>

    <x-card>
        <div class="divide-y divide-slate-100">
            @forelse ($faqs as $faq)
                <div class="flex flex-wrap items-start gap-4 py-4">
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-navy">{{ $faq->question }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $faq->answer }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge :on="$faq->is_published" />
                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-sm font-medium text-navy hover:text-brand">Edit</a>
                        <x-delete-button :action="route('admin.faqs.destroy', $faq)" />
                    </div>
                </div>
            @empty
                <p class="py-4 text-sm text-slate-500">No questions yet.</p>
            @endforelse
        </div>
    </x-card>
@endsection
