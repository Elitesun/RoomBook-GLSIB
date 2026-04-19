<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-800">Demandes en attente</h1>
    </x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('responsable.planning') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
            Planning global
        </a>
    </x-slot>

    <x-alert />

    <p class="mb-2 text-xs text-slate-400 sm:hidden">Faites glisser le tableau pour voir les actions.</p>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-[960px] text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Enseignant</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Salle</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Créneau</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Motif</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Matériel</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Décision</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($bookings as $booking)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-medium text-slate-800">{{ $booking->user->name }}</p>
                            <p class="text-xs text-slate-400">{{ $booking->user->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-slate-700">{{ $booking->room->name }}</p>
                            <p class="text-xs text-slate-400">{{ $booking->room->building }}</p>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                            {{ $booking->starts_at->format('d/m H:i') }} – {{ $booking->ends_at->format('H:i') }}
                        </td>
                        <td class="px-4 py-3 text-slate-600 max-w-xs">{{ $booking->purpose }}</td>
                        <td class="px-4 py-3">
                            @forelse ($booking->equipment as $item)
                                <div class="text-xs text-slate-600">{{ $item->name }} ×{{ $item->pivot->quantity }}</div>
                            @empty
                                <span class="text-xs text-slate-400">Aucun</span>
                            @endforelse
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex flex-col gap-2">
                                {{-- Accepter --}}
                                <form method="POST" action="{{ route('responsable.accept', $booking) }}">
                                    @csrf
                                    <button class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                                        Accepter
                                    </button>
                                </form>
                                {{-- Refuser --}}
                                <form method="POST" action="{{ route('responsable.reject', $booking) }}" class="space-y-1">
                                    @csrf
                                    <input name="rejection_reason"
                                           class="block w-full rounded-lg border-slate-300 text-xs shadow-sm focus:border-red-400 focus:ring-red-400"
                                           placeholder="Motif du refus…" required />
                                    <button class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 ring-1 ring-red-200 hover:bg-red-100 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                        Refuser
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                            <svg class="mx-auto mb-3 h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><polyline points="12 6 12 12 16 14"/></svg>
                            Aucune demande en attente.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $bookings->links() }}</div>
</x-app-layout>
