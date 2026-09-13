<?php

namespace App\Models;

use App\Models\Lahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['lahan_id', 'jenis', 'recurring_pattern', 'next_date', 'status'];

    protected $casts = [
        'next_date' => 'date',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }
    public function logs()
    {
        return $this->hasMany(ScheduleLog::class)->latest('tanggal_dilakukan');
    }
}
