<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier la réservation</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert />

            @php
                $openTime = config('booking.open_time', '08:00');
                $closeTime = config('booking.close_time', '20:00');
            @endphp

            <div class="rounded-lg bg-white p-6 shadow">
                <form method="POST" action="{{ route('bookings.update', $booking) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="room_id" value="Salle" />
                        <select id="room_id" name="room_id" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="">Choisir une salle</option>
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
                            <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-1 block w-full" :value="old('starts_at', $booking->starts_at->format('Y-m-d\\TH:i'))" :min="now()->format('Y-m-d\\TH:i')" required />
                            <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="ends_at" value="Fin" />
                            <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-1 block w-full" :value="old('ends_at', $booking->ends_at->format('Y-m-d\\TH:i'))" :min="now()->format('Y-m-d\\TH:i')" required />
                            <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Horaires autorisés: {{ $openTime }} - {{ $closeTime }} (même jour).</p>

                    <div>
                        <x-input-label for="purpose" value="Motif" />
                        <textarea id="purpose" name="purpose" rows="4" class="mt-1 block w-full rounded border-gray-300" required>{{ old('purpose', $booking->purpose) }}</textarea>
                        <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                    </div>

                    <div class="rounded border bg-gray-50 p-3 text-sm text-gray-700">
                        Modifier une réservation la remet en attente de validation.
                    </div>

                    <div class="flex gap-3">
                        <x-primary-button>Enregistrer les modifications</x-primary-button>
                        <a href="{{ route('bookings.index') }}" class="rounded border px-3 py-2 text-sm">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
