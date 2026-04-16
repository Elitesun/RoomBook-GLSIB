<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tableau de bord admin</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert />

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded bg-white p-4 shadow"><div class="text-xs text-gray-500">Salles</div><div class="text-2xl font-bold">{{ $stats['rooms_count'] }}</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-xs text-gray-500">Équipements</div><div class="text-2xl font-bold">{{ $stats['equipment_count'] }}</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-xs text-gray-500">En attente</div><div class="text-2xl font-bold">{{ $stats['pending_count'] }}</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-xs text-gray-500">Réservations cette semaine</div><div class="text-2xl font-bold">{{ $stats['bookings_this_week'] }}</div></div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                <a href="{{ route('admin.rooms.index') }}" class="rounded border bg-white p-4 text-sm shadow hover:bg-gray-50">Gérer les salles</a>
                <a href="{{ route('admin.equipment.index') }}" class="rounded border bg-white p-4 text-sm shadow hover:bg-gray-50">Gérer le matériel</a>
                <a href="{{ route('admin.users.index') }}" class="rounded border bg-white p-4 text-sm shadow hover:bg-gray-50">Gérer les utilisateurs</a>
            </div>
        </div>
    </div>
</x-app-layout>
