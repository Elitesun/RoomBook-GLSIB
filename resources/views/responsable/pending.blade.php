<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Demandes en attente</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert />

            <div class="mb-4">
                <a href="{{ route('responsable.planning') }}" class="rounded border px-3 py-2 text-sm hover:bg-gray-50">Voir planning global</a>
            </div>

            <p class="mb-2 text-xs text-gray-500 sm:hidden">Faites glisser le tableau horizontalement pour voir les actions.</p>

            <div class="overflow-x-auto rounded-lg bg-white shadow">
                <table class="min-w-[920px] divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Enseignant</th>
                            <th class="px-4 py-3 text-left">Salle</th>
                            <th class="px-4 py-3 text-left">Créneau</th>
                            <th class="px-4 py-3 text-left">Motif</th>
                            <th class="px-4 py-3 text-left">Matériel</th>
                            <th class="px-4 py-3 text-left">Accepter</th>
                            <th class="px-4 py-3 text-left">Refuser</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($bookings as $booking)
                            <tr>
                                <td class="px-4 py-3">{{ $booking->user->name }}</td>
                                <td class="px-4 py-3">{{ $booking->room->name }}</td>
                                <td class="px-4 py-3">{{ $booking->starts_at->format('d/m H:i') }} - {{ $booking->ends_at->format('H:i') }}</td>
                                <td class="px-4 py-3">{{ $booking->purpose }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @forelse ($booking->equipment as $item)
                                        <div class="text-xs">{{ $item->name }} x{{ $item->pivot->quantity }}</div>
                                    @empty
                                        <span class="text-xs text-gray-400">Aucun</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-3 align-top">
                                    <form method="POST" action="{{ route('responsable.accept', $booking) }}">
                                        @csrf
                                        <button class="rounded px-3 py-1 text-xs" style="background-color: #16a34a; border: 1px solid #15803d; color: #ffffff;">Accepter</button>
                                    </form>
                                </td>
                                <td class="px-4 py-3 align-top">
                                    <form method="POST" action="{{ route('responsable.reject', $booking) }}">
                                        @csrf
                                        <input name="rejection_reason" class="mb-1 w-full rounded border-gray-300 text-xs" placeholder="Motif du refus" required />
                                        <button class="w-full rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-500">Refuser</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucune demande en attente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $bookings->links() }}</div>
        </div>
    </div>
</x-app-layout>
