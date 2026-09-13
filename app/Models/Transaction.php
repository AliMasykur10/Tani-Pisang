<?php

namespace App\Models;

use App\Models\Lahan;
use App\Models\User;
use App\Models\PanenCycle;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lahan_id',
        'user_id',
        'panen_cycle_id',
        'asset_id',
        'jenis',
        'kategori',
        'jumlah',
        'is_cash',
        'tanggal',
        'keterangan',
        'foto_bukti',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_cash' => 'boolean',
        'jumlah' => 'decimal:2',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function panenCycle()
    {
        return $this->belongsTo(PanenCycle::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
