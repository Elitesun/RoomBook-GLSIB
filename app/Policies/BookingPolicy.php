<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        return $user->role === 'admin'
            || $user->role === 'responsable'
            || $booking->user_id === $user->id;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id
            && $booking->status === 'acceptee'
            && $booking->starts_at->isFuture();
    }

    public function update(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id
            && $booking->status !== 'annulee'
            && $booking->starts_at->isFuture();
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id
            && $booking->status !== 'annulee'
            && $booking->starts_at->isFuture();
    }
}
