<?php

namespace App\Policies;

use App\Models\TroubleReport;
use App\Models\User;

class TroubleReportPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TroubleReport $troubleReport): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TroubleReport $troubleReport): bool
    {
        return true; // semua bisa update status/tindak lanjut, karena kerja tim
    }

    public function delete(User $user, TroubleReport $troubleReport): bool
    {
        return $user->role === 'admin'; // hapus laporan cuma admin
    }
}
