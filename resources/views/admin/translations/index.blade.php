@extends('layouts.admin')
@section('title', 'Arabic text')
@section('heading', 'Arabic text')
@section('subheading', 'Arabic version of everything the website shows. Empty fields fall back to English. When you change English text elsewhere in the CRM, its Arabic shows up here as missing.')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2 text-sm">
            <a href="{{ route('admin.translations.index', ['show' => 'all', 'q' => $search]) }}"
               class="rounded-lg px-3 py-2 {{ $filter !== 'missing' ? 'bg-navy font-medium text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">All ({{ $total }})</a>
            <a href="{{ route('admin.translations.index', ['show' => 'missing', 'q' => $search]) }}"
               class="rounded-lg px-3 py-2 {{ $filter === 'missing' ? 'bg-navy font-medium text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">Missing ({{ $total - $done }})</a>
            <span class="ml-2 text-slate-500">{{ $total ? round($done / $total * 100) : 0 }}% translated</span>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="hidden" name="show" value="{{ $filter }}">
            <input type="search" name="q" value="{{ $search }}" placeholder="Search English or Arabic"
                   class="w-64 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">
            <button class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Search</button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.translations.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-card>
            <div class="divide-y divide-slate-100">
                @forelse ($rows as $i => $row)
                    <div class="grid gap-3 py-4 md:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">English</p>
                            <p class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $row['source'] }}</p>
                            <input type="hidden" name="items[{{ $i }}][source]" value="{{ $row['source'] }}">
                        </div>
                        <div>
                            <label for="ar-{{ $row['hash'] }}" class="text-xs font-semibold uppercase tracking-wide {{ $row['text'] === '' ? 'text-brand' : 'text-slate-400' }}">
                                العربية {{ $row['text'] === '' ? '· missing' : '' }}
                            </label>
                            <textarea id="ar-{{ $row['hash'] }}" name="items[{{ $i }}][text]" dir="rtl" lang="ar"
                                      rows="{{ max(1, min(8, (int) ceil(mb_strlen($row['source']) / 70))) }}"
                                      class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand">{{ $row['text'] }}</textarea>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-sm text-slate-500">Nothing to show.</p>
                @endforelse
            </div>
        </x-card>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <x-submit>Save this page</x-submit>
            <div>{{ $rows->links() }}</div>
        </div>
    </form>
@endsection
