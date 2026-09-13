<?php

namespace App\Models;

use App\Models\AssetAllocation;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis',
        'tanggal_beli',
        'harga_beli',
        'kondisi',
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
    ];

    public function allocations()
    {
        return $this->hasMany(AssetAllocation::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
