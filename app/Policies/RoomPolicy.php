<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function before(User $user): bool|null
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'responsable', 'enseignant'], true);
    }

    public function view(User $user, Room $room): bool
    {
        return $this->viewAny($user);
    }
}
