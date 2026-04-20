<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes réservations</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert />

            <div class="mb-4 flex justify-between">
                <a href="{{ route('rooms.planning') }}" class="rounded border px-3 py-2 text-sm hover:bg-gray-50">Voir planning</a>
                <a href="{{ route('bookings.create') }}" class="rounded px-3 py-2 text-sm font-semibold" style="background-color: #2563eb; border: 1px solid #1d4ed8; color: #ffffff;">Nouvelle réservation</a>
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Salle</th>
                            <th class="px-4 py-3 text-left">Début</th>
                            <th class="px-4 py-3 text-left">Fin</th>
                            <th class="px-4 py-3 text-left">Statut</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($bookings as $booking)
                            <tr>
                                <td class="px-4 py-3">{{ $booking->room->name }}</td>
                                <td class="px-4 py-3">{{ $booking->starts_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">{{ $booking->ends_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3"><x-status-badge :status="$booking->status" /></td>
                                <td class="px-4 py-3">
                                    @if ($booking->starts_at->isFuture() && $booking->status !== 'annulee')
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('bookings.edit', $booking) }}" class="rounded border px-3 py-1 text-xs hover:bg-gray-50">Modifier</a>

                                            <form method="POST" action="{{ route('bookings.destroy', $booking) }}" onsubmit="return confirm('Supprimer cette réservation ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded bg-red-600 px-3 py-1 text-xs text-white hover:bg-red-500">Supprimer</button>
                                            </form>

                                            @if ($booking->status === 'acceptee')
                                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('{{ $booking->starts_at->lte(now()->addMinutes(30)) ? 'Cette réservation commence dans moins de 30 minutes. Confirmer l\'annulation ?' : 'Confirmer l\'annulation ?' }}');">
                                                    @csrf
                                                    <button type="submit" class="rounded bg-red-600 px-3 py-1 text-xs text-white hover:bg-red-500">Annuler</button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Aucune réservation.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $bookings->links() }}</div>
        </div>
    </div>
</x-app-layout>
