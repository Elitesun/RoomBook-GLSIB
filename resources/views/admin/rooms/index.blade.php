<x-app-layout>
    <x-slot name="header"><h1 class="text-base font-semibold text-slate-800">Salles</h1></x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('admin.rooms.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-amber-600 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
            Ajouter une salle
        </a>
    </x-slot>
    <x-alert />
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nom</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Bâtiment</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Capacité</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($rooms as $room)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $room->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $room->building }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $room->capacity }} places</td>
                        <td class="px-4 py-3">
                            @if($room->is_available)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 ring-1 ring-emerald-200"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Disponible</span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600 ring-1 ring-slate-200"><span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Indisponible</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.rooms.edit', $room) }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">Modifier</a>
                                <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Supprimer cette salle ?')" class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-red-200 hover:bg-red-100 transition-colors">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $rooms->links() }}</div>
</x-app-layout>
