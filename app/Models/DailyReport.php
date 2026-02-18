<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'report_date',
        'data',
        'telegram_sent_at',
        'telegram_status',
    ];

    protected $casts = [
        'report_date' => 'date',
        'data' => 'array', // JSON blob auto-decoded
        'telegram_sent_at' => 'datetime',
    ];
}
