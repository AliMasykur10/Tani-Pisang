<?php

namespace App\Models;

use App\Models\Asset;
use App\Models\Lahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAllocation extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id', 'lahan_id', 'porsi_persen'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }
}
