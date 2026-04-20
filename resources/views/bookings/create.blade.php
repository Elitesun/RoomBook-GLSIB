<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nouvelle réservation</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert />

            <div class="rounded-lg bg-white p-6 shadow">
                @php
                    $openTime = config('booking.open_time', '08:00');
                    $closeTime = config('booking.close_time', '20:00');
                    $equipmentSelectionState = $equipment->getCollection()
                        ->mapWithKeys(fn ($item) => [(string) $item->id => (int) old('equipment.'.$item->id, 0) > 0])
                        ->toArray();
                @endphp

                <form method="GET" action="{{ route('bookings.create') }}" class="mb-5 flex gap-2">
                    <x-text-input type="text" name="equipment_search" class="block w-full" :value="$equipmentSearch" placeholder="Rechercher un matériel..." />
                    <button type="submit" class="rounded border px-3 py-2 text-sm">Rechercher</button>
                </form>

                <form method="POST" action="{{ route('bookings.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="room_id" value="Salle" />
                        <select id="room_id" name="room_id" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="">Choisir une salle</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                                    {{ $room->name }} ({{ $room->building }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="starts_at" value="Début" />
                            <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-1 block w-full" :value="old('starts_at')" :min="now()->format('Y-m-d\\TH:i')" required />
                            <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="ends_at" value="Fin" />
                            <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-1 block w-full" :value="old('ends_at')" :min="now()->format('Y-m-d\\TH:i')" required />
                            <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Horaires autorisés: {{ $openTime }} - {{ $closeTime }} (même jour).</p>

                    <div>
                        <x-input-label for="purpose" value="Motif" />
                        <textarea id="purpose" name="purpose" rows="4" class="mt-1 block w-full rounded border-gray-300" required>{{ old('purpose') }}</textarea>
                        <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                    </div>

                    <div x-data="{ selected: @js($equipmentSelectionState) }">
                        <h3 class="text-sm font-semibold text-gray-700">Matériel (optionnel)</h3>
                        <p class="text-xs text-gray-500">Recherchez un matériel ou naviguez entre les pages.</p>
                        <div class="mt-2 space-y-2">
                            @foreach ($equipment as $item)
                                <div class="rounded border p-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <label class="text-sm font-medium flex items-center gap-2">
                                            <input type="checkbox" x-model="selected['{{ $item->id }}']">
                                            <span>{{ $item->name }} (stock: {{ $item->quantity }})</span>
                                        </label>
                                        <x-text-input type="number" min="0" max="{{ $item->quantity }}" name="equipment[{{ $item->id }}]" class="w-24" :value="old('equipment.'.$item->id, 0)" x-bind:disabled="!selected['{{ $item->id }}']" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">{{ $equipment->links() }}</div>
                        <x-input-error :messages="$errors->get('equipment')" class="mt-2" />
                    </div>

                    <div class="flex gap-3">
                        <x-primary-button>Envoyer la demande</x-primary-button>
                        <a href="{{ route('bookings.index') }}" class="rounded border px-3 py-2 text-sm">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
