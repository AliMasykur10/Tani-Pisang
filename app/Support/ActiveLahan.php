<?php

namespace App\Support;

use App\Models\Lahan;

class ActiveLahan
{
    public static function id(): ?int
    {
        return session('active_lahan_id');
    }

    public static function get(): ?Lahan
    {
        $id = self::id();

        return $id ? Lahan::find($id) : null;
    }

    public static function isAllSelected(): bool
    {
        return self::id() === null;
    }
}
