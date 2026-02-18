<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired when an admin marks a booking as COMPLETED.
 * Triggers the Follow-Up Resolver to schedule individualized reminders.
 */
class BookingCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Booking $booking
    ) {
    }
}
