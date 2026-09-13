<?php

namespace App\Policies;

use App\Models\PanenCycle;
use App\Models\User;

class PanenCyclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, PanenCycle $panenCycle): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, PanenCycle $panenCycle): bool
    {
        return $user->role === 'admin';
    }
}
