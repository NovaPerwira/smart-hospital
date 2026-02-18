<?php

namespace App\Providers;

use App\Events\BookingCompleted;
use App\Events\BookingCreated;
use App\Listeners\ScheduleFollowUps;
use App\Listeners\SendBookingConfirmation;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
            // Phase 1: Instant confirmation on booking creation
        BookingCreated::class => [
            SendBookingConfirmation::class,
        ],

            // Follow-Up Automation: Schedule reminders when booking is completed
        BookingCompleted::class => [
            ScheduleFollowUps::class,
        ],
    ];
}
