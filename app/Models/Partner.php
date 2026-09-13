<?php

namespace App\Models;

use App\Models\PartnerAgreement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = ['tipe', 'nama', 'kontak', 'catatan'];

    public function agreements()
    {
        return $this->hasMany(PartnerAgreement::class);
    }
}
