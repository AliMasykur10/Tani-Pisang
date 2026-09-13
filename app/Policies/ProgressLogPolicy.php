<?php

namespace App\Policies;

use App\Models\ProgressLog;
use App\Models\User;

class ProgressLogPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Admin & Staff sama-sama boleh lihat
    }

    public function view(User $user, ProgressLog $progressLog): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true; // Admin & Staff sama-sama boleh input
    }

    public function update(User $user, ProgressLog $progressLog): bool
    {
        return $user->role === 'admin' || $user->id === $progressLog->user_id;
    }

    public function delete(User $user, ProgressLog $progressLog): bool
    {
        return $user->role === 'admin' || $user->id === $progressLog->user_id;
    }
}
