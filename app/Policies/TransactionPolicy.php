<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Transaction $transaction): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->role === 'admin';
    }
}
