<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-800">Nouvelle réservation</h1>
    </x-slot>

    <x-alert />

    @php
        $openTime  = config('booking.open_time',  '08:00');
        $closeTime = config('booking.close_time', '20:00');
        $equipmentSelectionState = $equipment->getCollection()
            ->mapWithKeys(fn($item) => [(string)$item->id => (int)old('equipment.'.$item->id, 0) > 0])
            ->toArray();
    @endphp

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Formulaire principal --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Section salle & créneau --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold text-slate-700">Salle et créneau</h2>

                <form method="GET" action="{{ route('bookings.create') }}" class="mb-5 flex gap-2">
                    <x-text-input type="text" name="equipment_search" class="block w-full" :value="$equipmentSearch" placeholder="Rechercher un matériel..." />
                    <button type="submit" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 transition-colors">Chercher</button>
                </form>

                <form method="POST" action="{{ route('bookings.store') }}" class="space-y-4" id="booking-form">
                    @csrf

                    <div>
                        <x-input-label for="room_id" value="Salle" />
                        <select id="room_id" name="room_id"
                                class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">— Choisir une salle —</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                                    {{ $room->name }} — {{ $room->building }} ({{ $room->capacity }} places)
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="starts_at" value="Début" />
                            <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-1 block w-full"
                                          :value="old('starts_at')" :min="now()->format('Y-m-d\TH:i')" required />
                            <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="ends_at" value="Fin" />
                            <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-1 block w-full"
                                          :value="old('ends_at')" :min="now()->format('Y-m-d\TH:i')" required />
                            <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
                        </div>
                    </div>
                    <p class="text-xs text-slate-400">Horaires autorisés : {{ $openTime }} – {{ $closeTime }} (même journée).</p>

                    <div>
                        <x-input-label for="purpose" value="Motif de la réservation" />
                        <textarea id="purpose" name="purpose" rows="3"
                                  class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('purpose') }}</textarea>
                        <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                    </div>

                    {{-- Matériels --}}
                    <div x-data="{ selected: @js($equipmentSelectionState) }">
                        <h3 class="mb-2 text-sm font-semibold text-slate-700">Matériel <span class="font-normal text-slate-400">(optionnel)</span></h3>
                        <div class="space-y-2">
                            @foreach ($equipment as $item)
                                <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                                    <label class="flex cursor-pointer items-center gap-3 text-sm">
                                        <input type="checkbox" x-model="selected['{{ $item->id }}']"
                                               class="rounded border-slate-300 text-blue-500 focus:ring-blue-500">
                                        <span class="font-medium text-slate-700">{{ $item->name }}</span>
                                        <span class="text-xs text-slate-400">stock : {{ $item->quantity }}</span>
                                    </label>
                                    <x-text-input type="number" min="0" max="{{ $item->quantity }}"
                                                  name="equipment[{{ $item->id }}]" class="w-20"
                                                  :value="old('equipment.'.$item->id, 0)"
                                                  x-bind:disabled="!selected['{{ $item->id }}']" />
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">{{ $equipment->links() }}</div>
                        <x-input-error :messages="$errors->get('equipment')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <x-primary-button>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            Envoyer la demande
                        </x-primary-button>
                        <a href="{{ route('bookings.index') }}"
                           class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Panneau latéral info --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
                <p class="mb-1 font-semibold">Comment ça marche ?</p>
                <ol class="mt-2 space-y-1.5 text-xs text-blue-700 list-decimal list-inside">
                    <li>Choisissez votre salle et votre créneau.</li>
                    <li>Ajoutez du matériel si besoin.</li>
                    <li>Soumettez — un responsable valide.</li>
                    <li>Vous recevez un email de confirmation.</li>
                </ol>
            </div>
            <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 text-xs text-amber-800">
                <p class="font-semibold mb-1">Annulation</p>
                Une réservation acceptée peut être annulée jusqu'à 30 minutes avant son début.
            </div>
        </div>
    </div>
</x-app-layout>
