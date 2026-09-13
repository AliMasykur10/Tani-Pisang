<?php

namespace App\Models;

use App\Models\Lahan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LahanPhaseLog extends Model
{
    use HasFactory;

    protected $fillable = ['lahan_id', 'fase', 'tanggal_mulai', 'changed_by'];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
