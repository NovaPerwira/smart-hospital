<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FollowUpRule extends Model
{
    protected $fillable = [
        'treatment_id',
        'trigger_event',
        'interval_value',
        'interval_unit',
        'channel',
        'message_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'interval_value' => 'integer',
    ];

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }

    public function scheduledFollowUps(): HasMany
    {
        return $this->hasMany(ScheduledFollowUp::class);
    }

    /**
     * Calculate the dispatch_at timestamp from a given base time.
     */
    public function calculateDispatchAt(\Carbon\Carbon $completedAt): \Carbon\Carbon
    {
        return match ($this->interval_unit) {
            'minutes' => $completedAt->copy()->addMinutes($this->interval_value),
            'hours' => $completedAt->copy()->addHours($this->interval_value),
            'days' => $completedAt->copy()->addDays($this->interval_value),
            'weeks' => $completedAt->copy()->addWeeks($this->interval_value),
            'months' => $completedAt->copy()->addMonths($this->interval_value),
            default => $completedAt->copy()->addDays($this->interval_value),
        };
    }

    /**
     * Compile the message template with booking context.
     */
    public function compileMessage(Booking $booking): string
    {
        return str_replace(
            ['{patient_name}', '{doctor_name}', '{treatment_name}', '{booking_code}'],
            [
                $booking->patient_name,
                $booking->doctor->name,
                $booking->treatment->name,
                $booking->booking_code,
            ],
            $this->message_template
        );
    }
}
