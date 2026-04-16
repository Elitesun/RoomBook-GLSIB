@props(['weekStart', 'routeName'])

@php
    $previousWeek = $weekStart->copy()->subWeek()->format('o-W');
    $nextWeek = $weekStart->copy()->addWeek()->format('o-W');
@endphp

<div class="mb-4 flex items-center justify-between gap-3">
    <a href="{{ route($routeName, ['week' => $previousWeek]) }}" class="rounded border px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
        Semaine précédente
    </a>

    <span class="text-sm font-medium text-gray-700">
        Semaine du {{ $weekStart->format('d/m/Y') }}
    </span>

    <a href="{{ route($routeName, ['week' => $nextWeek]) }}" class="rounded border px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
        Semaine suivante
    </a>
</div>
