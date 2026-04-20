<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base font-semibold text-slate-800">Modifier la réservation</h1>
    </x-slot>

    <x-alert />

    @php
        $openTime  = config('booking.open_time',  '08:00');
        $closeTime = config('booking.close_time', '20:00');
    @endphp

    <div class="max-w-2xl">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="mb-5 flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
                <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                Modifier une réservation la remet en attente de validation.
            </div>

            <form method="POST" action="{{ route('bookings.update', $booking) }}" class="space-y-4">
                @csrf @method('PUT')

                <div>
                    <x-input-label for="room_id" value="Salle" />
                    <select id="room_id" name="room_id"
                            class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">— Choisir une salle —</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" @selected(old('room_id', $booking->room_id) == $room->id)>
                                {{ $room->name }} ({{ $room->building }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="starts_at" value="Début" />
                        <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-1 block w-full"
                                      :value="old('starts_at', $booking->starts_at->format('Y-m-d\TH:i'))"
                                      :min="now()->format('Y-m-d\TH:i')" required />
                        <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="ends_at" value="Fin" />
                        <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-1 block w-full"
                                      :value="old('ends_at', $booking->ends_at->format('Y-m-d\TH:i'))"
                                      :min="now()->format('Y-m-d\TH:i')" required />
                        <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
                    </div>
                </div>
                <p class="text-xs text-slate-400">Horaires autorisés : {{ $openTime }} – {{ $closeTime }} (même journée).</p>

                <div>
                    <x-input-label for="purpose" value="Motif" />
                    <textarea id="purpose" name="purpose" rows="3"
                              class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('purpose', $booking->purpose) }}</textarea>
                    <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Enregistrer les modifications</x-primary-button>
                    <a href="{{ route('bookings.index') }}"
                       class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 transition-colors">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
