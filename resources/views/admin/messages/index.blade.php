@extends('layouts.admin')
@section('title', 'Messages')
@section('heading', 'Messages')
@section('subheading', 'Enquiries sent through the website contact form.')

@section('content')
    <x-card>
        <div class="divide-y divide-slate-100">
            @forelse ($messages as $message)
                <a href="{{ route('admin.messages.show', $message) }}" class="flex flex-wrap items-baseline justify-between gap-2 py-3 hover:text-brand">
                    <span class="{{ $message->read_at ? 'text-slate-600' : 'font-semibold text-navy' }}">
                        {{ $message->name }} <span class="text-sm font-normal text-slate-500">· {{ $message->email }}</span>
                    </span>
                    <span class="text-sm text-slate-500">
                        {{ $message->subject ?: 'No subject' }} · {{ $message->created_at->format('d M Y, H:i') }}
                        @unless ($message->read_at)<span class="ml-2 rounded-full bg-brand px-2 py-0.5 text-xs text-white">new</span>@endunless
                    </span>
                </a>
            @empty
                <p class="py-4 text-sm text-slate-500">No messages yet.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $messages->links() }}</div>
    </x-card>
@endsection
