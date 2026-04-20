<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $pageTitle }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-week-nav :weekStart="$weekStart" :routeName="request()->route()->getName()" />
            @if (! $showAllStatuses)
                <div class="mb-4 text-right">
                    <a href="{{ route('bookings.create') }}" class="rounded px-3 py-2 text-sm font-semibold" style="background-color: #2563eb; border: 1px solid #1d4ed8; color: #ffffff;">Nouvelle réservation</a>
                </div>
            @endif

            <div class="overflow-x-auto rounded-lg bg-white shadow">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Salle</th>
                            @foreach ($days as $day)
                                <th class="px-4 py-3 text-left">{{ ucfirst($day->locale('fr')->translatedFormat('D d/m')) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($rooms as $room)
                            <tr>
                                <td class="px-4 py-3 font-medium {{ $room->is_available ? '' : 'text-gray-400 line-through' }}">
                                    {{ $room->name }}
                                </td>
                                @foreach ($days as $day)
                                    @php
                                        $cellBookings = $room->bookings->filter(fn ($booking) => $booking->starts_at->isSameDay($day));
                                    @endphp
                                    <td class="px-3 py-3 align-top">
                                        @if (! $room->is_available)
                                            <span class="inline-block rounded bg-gray-200 px-2 py-1 text-xs text-gray-700">Indisponible</span>
                                        @elseif ($cellBookings->isEmpty())
                                            <span class="inline-block rounded bg-green-100 px-2 py-1 text-xs text-green-700">Libre</span>
                                        @else
                                            <div class="space-y-1">
                                                @foreach ($cellBookings as $booking)
                                                    @php
                                                        $color = match ($booking->status) {
                                                            'acceptee' => 'bg-red-100 text-red-800',
                                                            'en_attente' => 'bg-orange-100 text-orange-800',
                                                            'refusee' => 'bg-gray-100 text-gray-700',
                                                            default => 'bg-slate-100 text-slate-700',
                                                        };
                                                    @endphp
                                                    @if ($showAllStatuses || in_array($booking->status, ['en_attente', 'acceptee'], true))
                                                        <div class="rounded px-2 py-1 text-xs {{ $color }}" title="{{ $booking->purpose }}">
                                                            {{ $booking->starts_at->format('H:i') }}-{{ $booking->ends_at->format('H:i') }}
                                                            @if ($showAllStatuses)
                                                                ({{ $booking->user->name }})
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
        </div>
    </div>
</x-app-layout>
