<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Phase 2 — H-1 Reminder
 * Dispatched by the SendH1Reminders command (Cron: 08:00 Daily).
 * Only sent to bookings with status='confirmed' and no reminder yet sent.
 */
class SendH1ReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function handle(NotificationService $notificationService): void
    {
        $message = $notificationService->buildReminderMessage($this->booking);
        $notificationService->send($this->booking, 'reminder', $message);

        $this->booking->update(['reminder_sent_at' => now()]);
    }
}
