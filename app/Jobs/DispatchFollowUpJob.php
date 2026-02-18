<?php

namespace App\Jobs;

use App\Models\ScheduledFollowUp;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Queue Worker Dispatch Job
 *
 * Dispatched by DispatchFollowUps command (every 5 minutes).
 * Sends the follow-up notification and marks status=sent.
 */
class DispatchFollowUpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ScheduledFollowUp $followUp)
    {
    }

    public function handle(NotificationService $notificationService): void
    {
        $followUp = $this->followUp->load('booking');

        try {
            // Send via NotificationService (logs to notification_logs table)
            $notificationService->send(
                $followUp->booking,
                'follow_up',
                $followUp->message
            );

            // UPDATE status=sent
            $followUp->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Log::info('[follow_up] Dispatched follow-up', [
                'id' => $followUp->id,
                'booking_code' => $followUp->booking->booking_code,
                'recipient' => $followUp->recipient,
            ]);

        } catch (\Throwable $e) {
            $followUp->update(['status' => 'failed']);
            Log::error('[follow_up] Failed to dispatch follow-up', [
                'id' => $followUp->id,
                'error' => $e->getMessage(),
            ]);
            throw $e; // Let the queue retry
        }
    }
}
