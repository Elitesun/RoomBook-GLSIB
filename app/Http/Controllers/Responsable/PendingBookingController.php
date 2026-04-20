<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Mail\BookingAccepted;
use App\Mail\BookingRejected;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PendingBookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::query()
            ->where('status', 'en_attente')
            ->with(['user', 'room', 'equipment'])
            ->orderBy('starts_at')
            ->paginate(10);

        return view('responsable.pending', compact('bookings'));
    }

    public function accept(Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'en_attente') {
            return redirect()->route('responsable.pending')->with('error', 'Cette demande n\'est plus en attente.');
        }

        $booking->update([
            'status' => 'acceptee',
            'rejection_reason' => null,
        ]);

        Mail::to($booking->user)->send(new BookingAccepted($booking->fresh(['user', 'room'])));

        return redirect()->route('responsable.pending')->with('success', 'Réservation acceptée.');
    }

    public function reject(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'en_attente') {
            return redirect()->route('responsable.pending')->with('error', 'Cette demande n\'est plus en attente.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $booking->update([
            'status' => 'refusee',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        Mail::to($booking->user)->send(new BookingRejected($booking->fresh(['user', 'room'])));

        return redirect()->route('responsable.pending')->with('success', 'Réservation refusée.');
    }
}
