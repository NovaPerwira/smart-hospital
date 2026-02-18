<?php

namespace App\Listeners;

use App\Events\BookingCompleted;
use App\Jobs\ScheduleFollowUpsJob;

/**
 * Follow-Up Resolver — listens for BookingCompleted and dispatches the scheduling job.
 */
class ScheduleFollowUps
{
    public function handle(BookingCompleted $event): void
    {
        // Dispatch to queue so the resolver doesn't block the HTTP response
        ScheduleFollowUpsJob::dispatch($event->booking);
    }
}
