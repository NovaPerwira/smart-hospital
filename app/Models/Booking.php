<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasUuids;

    protected $fillable = [
        'booking_code',
        'patient_name',
        'patient_phone',
        'doctor_id',
        'treatment_id',
        'booking_date',
        'slot_id',
        'status',
        'reminder_sent_at',
        'confirmation_token',
        'confirmed_at',
        'cancellation_pending',
        'arrived_at',
        'completed_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'reminder_sent_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'arrived_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancellation_pending' => 'boolean',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(BookingSlot::class, 'slot_id');
    }

    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function scheduledFollowUps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ScheduledFollowUp::class);
    }
}
