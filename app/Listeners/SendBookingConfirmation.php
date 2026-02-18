<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Jobs\SendBookingConfirmationJob;

/**
 * Listens for BookingCreated and dispatches the Phase 1 confirmation job to the queue.
 */
class SendBookingConfirmation
{
    public function handle(BookingCreated $event): void
    {
        // Dispatch to queue — Phase 1: Instant Confirmation (T+0)
        SendBookingConfirmationJob::dispatch($event->booking);
    }
}
