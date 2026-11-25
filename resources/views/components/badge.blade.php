@props(['on' => true, 'onLabel' => 'Published', 'offLabel' => 'Hidden'])
<span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $on ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
    {{ $on ? $onLabel : $offLabel }}
</span>
