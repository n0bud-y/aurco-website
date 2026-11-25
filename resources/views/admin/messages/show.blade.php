@extends('layouts.admin')
@section('title', 'Message')
@section('heading', 'Message')

@section('content')
    <x-card>
        <dl class="grid gap-4 sm:grid-cols-2">
            <div><dt class="text-xs uppercase tracking-wide text-slate-500">From</dt><dd class="text-navy">{{ $message->name }}</dd></div>
            <div><dt class="text-xs uppercase tracking-wide text-slate-500">Email</dt><dd><a class="text-brand hover:underline" href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd></div>
            <div><dt class="text-xs uppercase tracking-wide text-slate-500">Phone</dt><dd class="text-navy">{{ $message->phone ?: '—' }}</dd></div>
            <div><dt class="text-xs uppercase tracking-wide text-slate-500">Subject</dt><dd class="text-navy">{{ $message->subject ?: '—' }}</dd></div>
            <div><dt class="text-xs uppercase tracking-wide text-slate-500">Received</dt><dd class="text-navy">{{ $message->created_at->format('d M Y, H:i') }}</dd></div>
        </dl>

        <div class="mt-6 whitespace-pre-line rounded-lg bg-slate-50 p-4 text-slate-700">{{ $message->body }}</div>

        <div class="mt-6 flex items-center gap-4">
            <x-primary-link :href="'mailto:'.$message->email.'?subject='.rawurlencode('Re: '.($message->subject ?: 'Your enquiry'))">Reply by email</x-primary-link>
            <a href="{{ route('admin.messages.index') }}" class="text-sm text-slate-500 hover:text-navy">Back to messages</a>
            <x-delete-button :action="route('admin.messages.destroy', $message)" confirm="Delete this message?" />
        </div>
    </x-card>
@endsection
