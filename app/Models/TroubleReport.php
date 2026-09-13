<?php

namespace App\Models;

use App\Models\Lahan;
use App\Models\User;
use App\Models\TroubleUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TroubleReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lahan_id',
        'user_id',
        'judul',
        'deskripsi',
        'urgensi',
        'status',
        'foto_urls',
        'selesai_at',
    ];

    protected $casts = [
        'foto_urls' => 'array',
        'selesai_at' => 'datetime',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updates()
    {
        return $this->hasMany(TroubleUpdate::class);
    }
}
