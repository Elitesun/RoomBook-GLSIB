<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Equipment;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::query()
            ->where('user_id', auth()->id())
            ->with(['room', 'equipment'])
            ->latest('starts_at')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request): View
    {
        $equipmentSearch = trim((string) $request->query('equipment_search', ''));

        $rooms = Room::query()->where('is_available', true)->orderBy('name')->get();
        $equipment = Equipment::query()
            ->where('is_available', true)
            ->when($equipmentSearch !== '', function ($query) use ($equipmentSearch): void {
                $query->where('name', 'like', "%{$equipmentSearch}%");
            })
            ->orderBy('name')
            ->paginate(8)
            ->withQueryString();

        return view('bookings.create', compact('rooms', 'equipment', 'equipmentSearch'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $validated['room_id'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'purpose' => $validated['purpose'],
            'status' => 'en_attente',
            'rejection_reason' => null,
        ]);

        $equipmentData = collect($validated['equipment'] ?? [])
            ->filter(fn (mixed $quantity): bool => (int) $quantity > 0)
            ->mapWithKeys(fn (mixed $quantity, mixed $equipmentId): array => [(int) $equipmentId => ['quantity' => (int) $quantity]])
            ->toArray();

        if (! empty($equipmentData)) {
            $booking->equipment()->sync($equipmentData);
        }

        return redirect()->route('bookings.index')->with('success', 'Demande de réservation envoyée.');
    }

    public function edit(Booking $booking): View
    {
        $this->authorize('update', $booking);

        $rooms = Room::query()->where('is_available', true)->orderBy('name')->get();

        return view('bookings.edit', compact('booking', 'rooms'));
    }

    public function update(StoreBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        $validated = $request->validated();

        $booking->update([
            'room_id' => $validated['room_id'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'purpose' => $validated['purpose'],
            // Any teacher edit must go back to pending for re-validation.
            'status' => 'en_attente',
            'rejection_reason' => null,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Réservation mise à jour et remise en attente de validation.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Réservation supprimée.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        $booking->update([
            'status' => 'annulee',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Réservation annulée.');
    }
}
