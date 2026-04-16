<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;

class EquipmentPolicy
{
    public function before(User $user): bool|null
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'responsable', 'enseignant'], true);
    }

    public function view(User $user, Equipment $equipment): bool
    {
        return $this->viewAny($user);
    }
}
