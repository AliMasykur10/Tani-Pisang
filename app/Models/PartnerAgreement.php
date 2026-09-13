<?php

namespace App\Models;

use App\Models\Partner;
use App\Models\Lahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'lahan_id',
        'skema',
        'nominal_sewa',
        'persentase_bagi_hasil',
        'tanggal_mulai',
        'tanggal_berakhir',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'is_active' => 'boolean',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }
}
