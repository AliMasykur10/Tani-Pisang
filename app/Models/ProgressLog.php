<?php

namespace App\Models;

use App\Models\Lahan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['lahan_id', 'user_id', 'tanggal', 'keterangan', 'foto_urls'];

    protected $casts = [
        'tanggal' => 'date',
        'foto_urls' => 'array',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
