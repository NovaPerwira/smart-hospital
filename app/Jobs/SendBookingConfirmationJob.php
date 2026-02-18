<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

/**
 * Phase 1 — Instant Confirmation (T+0)
 * Dispatched immediately after BookingCreated event.
 * Generates a confirmation token and sends the confirmation message.
 */
class SendBookingConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function handle(NotificationService $notificationService): void
    {
        // Generate a secure confirmation token for Phase 3 links
        $this->booking->update([
            'confirmation_token' => Str::random(40),
        ]);

        $this->booking->refresh();

        $message = $notificationService->buildConfirmationMessage($this->booking);
        $notificationService->send($this->booking, 'confirmation', $message);
    }
}
