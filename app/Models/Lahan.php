<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\PartnerAgreement;
use App\Models\LahanPhaseLog;
use App\Models\PanenCycle;
use App\Models\ProgressLog;
use App\Models\TroubleReport;
use App\Models\Schedule;
use App\Models\AssetAllocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'luas_panjang_m',
        'luas_lebar_m',
        'jarak_tanam_m',
        'jarak_pagar_m',
        'estimasi_jumlah_pohon',
        'fase_saat_ini',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function partnerAgreements()
    {
        return $this->hasMany(PartnerAgreement::class);
    }

    public function activeAgreement()
    {
        return $this->hasOne(PartnerAgreement::class)->where('is_active', true);
    }

    public function phaseLogs()
    {
        return $this->hasMany(LahanPhaseLog::class);
    }

    public function panenCycles()
    {
        return $this->hasMany(PanenCycle::class);
    }

    public function progressLogs()
    {
        return $this->hasMany(ProgressLog::class);
    }

    public function troubleReports()
    {
        return $this->hasMany(TroubleReport::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function assetAllocations()
    {
        return $this->hasMany(AssetAllocation::class);
    }
}
