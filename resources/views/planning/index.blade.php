<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-800">{{ $pageTitle }}</h1>
    </x-slot>

    @if (! $showAllStatuses)
        <x-slot name="headerActions">
            <a href="{{ route('bookings.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nouvelle réservation
            </a>
        </x-slot>
    @endif

    <x-week-nav :weekStart="$weekStart" :routeName="request()->route()->getName()" />

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 w-36">Salle</th>
                    @foreach ($days as $day)
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500
                            {{ $day->isToday() ? 'text-blue-600' : '' }}">
                            {{ ucfirst($day->locale('fr')->translatedFormat('D d/m')) }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($rooms as $room)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-medium text-slate-800 {{ $room->is_available ? '' : 'text-slate-400 line-through' }}">
                                {{ $room->name }}
                            </p>
                            <p class="text-xs text-slate-400">{{ $room->building }}</p>
                        </td>
                        @foreach ($days as $day)
                            @php
                                $cellBookings = $room->bookings->filter(fn($b) => $b->starts_at->isSameDay($day));
                            @endphp
                            <td class="px-3 py-3 align-top min-w-[120px]">
                                @if (! $room->is_available)
                                    <span class="inline-block rounded-md bg-slate-100 px-2 py-1 text-xs text-slate-500">Indisponible</span>
                                @elseif ($cellBookings->isEmpty())
                                    <span class="inline-block rounded-md bg-emerald-50 px-2 py-1 text-xs text-emerald-700 ring-1 ring-emerald-200">Libre</span>
                                @else
                                    <div class="space-y-1">
                                        @foreach ($cellBookings as $booking)
                                            @php
                                                $pill = match($booking->status) {
                                                    'acceptee'  => 'bg-red-50 text-red-800 ring-red-200',
                                                    'en_attente'=> 'bg-amber-50 text-amber-800 ring-amber-200',
                                                    'refusee'   => 'bg-slate-100 text-slate-600 ring-slate-200',
                                                    default     => 'bg-slate-100 text-slate-600 ring-slate-200',
                                                };
                                            @endphp
                                            @if ($showAllStatuses || in_array($booking->status, ['en_attente', 'acceptee'], true))
                                                <div class="rounded-md px-2 py-1 text-xs ring-1 {{ $pill }}" title="{{ $booking->purpose }}">
                                                    <span class="font-medium">{{ $booking->starts_at->format('H:i') }}&ndash;{{ $booking->ends_at->format('H:i') }}</span>
                                                    @if ($showAllStatuses)
                                                        <br><span class="opacity-75">{{ $booking->user->name }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Légende --}}
    <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-slate-500">
        <span class="flex items-center gap-1.5"><span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-400"></span>Libre</span>
        <span class="flex items-center gap-1.5"><span class="inline-block h-2.5 w-2.5 rounded-full bg-amber-400"></span>En attente</span>
        <span class="flex items-center gap-1.5"><span class="inline-block h-2.5 w-2.5 rounded-full bg-red-400"></span>Acceptée</span>
        <span class="flex items-center gap-1.5"><span class="inline-block h-2.5 w-2.5 rounded-full bg-slate-300"></span>Refusée / Indisponible</span>
    </div>
</x-app-layout>
