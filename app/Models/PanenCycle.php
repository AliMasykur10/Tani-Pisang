<?php

namespace App\Models;

use App\Models\Lahan;
use App\Models\AnakanRecord;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanenCycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'lahan_id',
        'nomor_siklus',
        'tanggal_panen',
        'jumlah_pohon_produktif',
        'total_hasil_kg',
        'harga_per_kg',
        'total_pemasukan',
    ];

    protected $casts = [
        'tanggal_panen' => 'date',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }

    public function anakanRecord()
    {
        return $this->hasOne(AnakanRecord::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
