<?php

namespace App\Models;

use App\Models\TroubleReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TroubleUpdate extends Model
{
    use HasFactory;

    protected $fillable = ['trouble_report_id', 'user_id', 'komentar', 'foto_urls'];

    protected $casts = [
        'foto_urls' => 'array',
    ];

    public function troubleReport()
    {
        return $this->belongsTo(TroubleReport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
