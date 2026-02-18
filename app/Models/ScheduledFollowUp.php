<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledFollowUp extends Model
{
    protected $fillable = [
        'booking_id',
        'follow_up_rule_id',
        'dispatch_at',
        'message',
        'channel',
        'recipient',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'dispatch_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(FollowUpRule::class, 'follow_up_rule_id');
    }

    /**
     * Scope: due follow-ups (dispatch_at <= now AND status = pending).
     */
    public function scopeDue($query)
    {
        return $query->where('status', 'pending')
            ->where('dispatch_at', '<=', now());
    }
}
