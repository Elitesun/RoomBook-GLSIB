@props(['weekStart', 'routeName'])
@php
    $prev = $weekStart->copy()->subWeek()->format('o-W');
    $next = $weekStart->copy()->addWeek()->format('o-W');
@endphp
<div class="mb-5 flex items-center justify-between gap-3">
    <a href="{{ route($routeName, ['week' => $prev]) }}"
       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Précédente
    </a>
    <span class="text-sm font-medium text-slate-700">
        Semaine du {{ $weekStart->format('d/m/Y') }}
    </span>
    <a href="{{ route($routeName, ['week' => $next]) }}"
       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
        Suivante
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </a>
</div>
