<?php

namespace App\Models;

use App\Models\PanenCycle;
use App\Models\Lahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnakanRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'panen_cycle_id',
        'jumlah_muncul',
        'jumlah_disisakan',
        'jumlah_dijual',
        'jumlah_dipindah_lahan_lain',
        'jumlah_dibuang',
        'nilai_estimasi_per_batang',
        'lahan_tujuan_id',
    ];

    public function panenCycle()
    {
        return $this->belongsTo(PanenCycle::class);
    }

    public function lahanTujuan()
    {
        return $this->belongsTo(Lahan::class, 'lahan_tujuan_id');
    }
}
