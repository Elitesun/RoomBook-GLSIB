<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Salles</h2></x-slot>
    <div class="py-8"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"><x-alert />
        <div class="mb-4"><a href="{{ route('admin.rooms.create') }}" class="rounded border border-gray-900 bg-white px-3 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-100">Ajouter une salle</a></div>
        <div class="overflow-hidden rounded bg-white shadow"><table class="min-w-full text-sm"><thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left">Nom</th><th class="px-4 py-3 text-left">Capacité</th><th class="px-4 py-3 text-left">Bâtiment</th><th class="px-4 py-3 text-left">Disponible</th><th class="px-4 py-3 text-left">Actions</th></tr></thead><tbody class="divide-y">
            @foreach($rooms as $room)
            <tr><td class="px-4 py-3">{{ $room->name }}</td><td class="px-4 py-3">{{ $room->capacity }}</td><td class="px-4 py-3">{{ $room->building }}</td><td class="px-4 py-3">{{ $room->is_available ? 'Oui' : 'Non' }}</td><td class="px-4 py-3 flex gap-2"><a href="{{ route('admin.rooms.edit', $room) }}" class="rounded border px-2 py-1 text-xs">Modifier</a><form method="POST" action="{{ route('admin.rooms.destroy', $room) }}">@csrf @method('DELETE')<button class="rounded bg-red-600 px-2 py-1 text-xs text-white" onclick="return confirm('Supprimer ?')">Supprimer</button></form></td></tr>
            @endforeach
        </tbody></table></div><div class="mt-4">{{ $rooms->links() }}</div></div></div>
</x-app-layout>
