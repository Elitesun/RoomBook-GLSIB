<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\Equipment;
use App\Rules\EndAfterStart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => ['required', 'exists:rooms,id'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', new EndAfterStart($this->input('starts_at'))],
            'purpose' => ['required', 'string', 'max:5000'],
            'equipment' => ['nullable', 'array'],
            'equipment.*' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->filled(['room_id', 'starts_at', 'ends_at'])) {
                return;
            }

            $startsAt = Carbon::parse($this->input('starts_at'));
            $endsAt = Carbon::parse($this->input('ends_at'));
            $roomId = (int) $this->input('room_id');

            $openTime = (string) config('booking.open_time', '08:00');
            $closeTime = (string) config('booking.close_time', '20:00');

            if ($startsAt->lt(now())) {
                $validator->errors()->add('starts_at', 'La réservation doit commencer à une date/heure future.');
            }

            if (! $startsAt->isSameDay($endsAt)) {
                $validator->errors()->add('ends_at', 'La réservation doit commencer et se terminer le même jour.');
            }

            $startClock = $startsAt->format('H:i');
            $endClock = $endsAt->format('H:i');

            if ($startClock < $openTime || $startClock >= $closeTime) {
                $validator->errors()->add('starts_at', "L'heure de début doit être entre $openTime et $closeTime.");
            }

            if ($endClock <= $openTime || $endClock > $closeTime) {
                $validator->errors()->add('ends_at', "L'heure de fin doit être entre $openTime et $closeTime.");
            }

            $roomConflict = Booking::query()
                ->where('room_id', $roomId)
                ->where('status', 'acceptee')
                ->where(function ($query) use ($startsAt, $endsAt): void {
                    $query->where('starts_at', '<', $endsAt)
                        ->where('ends_at', '>', $startsAt);
                })
                ->first();

            if ($roomConflict) {
                $validator->errors()->add('room_id', 'Cette salle est déjà réservée sur ce créneau.');
            }

            $requestedEquipment = collect($this->input('equipment', []))
                ->filter(fn (mixed $quantity): bool => (int) $quantity > 0)
                ->mapWithKeys(fn (mixed $quantity, mixed $equipmentId): array => [(int) $equipmentId => (int) $quantity]);

            if ($requestedEquipment->isEmpty()) {
                return;
            }

            $overlappingBookings = Booking::query()
                ->where('status', 'acceptee')
                ->where(function ($query) use ($startsAt, $endsAt): void {
                    $query->where('starts_at', '<', $endsAt)
                        ->where('ends_at', '>', $startsAt);
                })
                ->with('equipment')
                ->get();

            $reservedQuantities = [];

            foreach ($overlappingBookings as $booking) {
                foreach ($booking->equipment as $equipment) {
                    $reservedQuantities[$equipment->id] = ($reservedQuantities[$equipment->id] ?? 0) + (int) $equipment->pivot->quantity;
                }
            }

            foreach ($requestedEquipment as $equipmentId => $requestedQuantity) {
                $equipment = Equipment::find($equipmentId);

                if (! $equipment || ! $equipment->is_available) {
                    $validator->errors()->add('equipment', 'Le matériel sélectionné est indisponible.');

                    continue;
                }

                $alreadyReserved = $reservedQuantities[$equipmentId] ?? 0;

                if (($alreadyReserved + $requestedQuantity) > $equipment->quantity) {
                    $validator->errors()->add('equipment', sprintf('Stock insuffisant pour %s.', $equipment->name));
                }
            }
        });
    }
}
