@extends('layouts.admin')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subheading', 'Everything on the website, in one place.')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($stats as $stat)
            <a href="{{ $stat['route'] }}" class="rounded-xl border border-slate-200 bg-white p-5 transition hover:border-brand hover:shadow-sm">
                <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>
                <p class="mt-2 text-3xl font-bold text-navy">{{ $stat['value'] }}</p>
            </a>
        @endforeach
    </div>

    <x-card title="Latest enquiries" :description="$unread ? $unread.' unread' : 'Nothing new right now'">
        @if ($messages->isEmpty())
            <p class="text-sm text-slate-500">Messages sent through the website contact form land here.</p>
        @else
            <div class="divide-y divide-slate-100">
                @foreach ($messages as $message)
                    <a href="{{ route('admin.messages.show', $message) }}" class="flex flex-wrap items-baseline justify-between gap-2 py-3 hover:text-brand">
                        <span class="font-medium {{ $message->read_at ? 'text-slate-600' : 'text-navy' }}">
                            {{ $message->name }}
                            @unless ($message->read_at)<span class="ml-2 rounded-full bg-brand px-2 py-0.5 text-xs text-white">new</span>@endunless
                        </span>
                        <span class="text-sm text-slate-500">{{ $message->subject ?: 'No subject' }} · {{ $message->created_at->diffForHumans() }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </x-card>
@endsection
