<?php

namespace App\Models;

use App\Models\Chair; // id: 33b2c3be-f61d-4594-9675-a742edc3bf28
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingSlot extends Model
{
    protected $fillable = [
        'doctor_id',
        'chair_id',
        'date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function chair(): BelongsTo
    {
        return $this->belongsTo(Chair::class);
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'slot_id');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }
}
