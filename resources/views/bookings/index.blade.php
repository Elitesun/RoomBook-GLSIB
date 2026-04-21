<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-800">Mes réservations</h1>
    </x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('bookings.create') }}"
           class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
            Nouvelle réservation
        </a>
    </x-slot>

    <x-alert />

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Salle</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Début</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Fin</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Motif</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($bookings as $booking)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-medium text-slate-800">{{ $booking->room->name }}</p>
                            <p class="text-xs text-slate-400">{{ $booking->room->building }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $booking->starts_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $booking->ends_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-slate-600 max-w-xs truncate">{{ $booking->purpose }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$booking->status" /></td>
                        <td class="px-4 py-3">
                            @if ($booking->starts_at->isFuture() && $booking->status !== 'annulee' )
                                <div class="flex flex-wrap gap-2">
                                    @if ($booking->status == 'en_attente')
                                    <a href="{{ route('bookings.edit', $booking) }}"
                                       class="inline-flex items-center rounded-md border border-slate-300 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('bookings.destroy', $booking) }}"
                                          onsubmit="return confirm('Supprimer cette réservation ?')">
                                        @csrf @method('DELETE')
                                        <button class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-red-200 hover:bg-red-100 transition-colors">
                                            Supprimer
                                        </button>
                                    </form>
                                    @endif
                                    @if ($booking->status === 'acceptee')
                                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                                              onsubmit="return confirm('{{ $booking->starts_at->lte(now()->addMinutes(30)) ? 'Début dans moins de 30 min. Confirmer ?' : 'Confirmer l\'annulation ?' }}')">
                                            @csrf
                                            <button class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-amber-200 hover:bg-amber-100 transition-colors">
                                                Annuler
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                            <svg class="mx-auto mb-3 h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                            Aucune réservation pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $bookings->links() }}</div>
</x-app-layout>
