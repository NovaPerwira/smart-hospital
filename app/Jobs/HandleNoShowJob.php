<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Phase 4 — No-Show Handling (T+2h after appointment)
 * Dispatched by the HandleNoShows command (Cron: T+2h after slot).
 * If arrived_at is NULL, marks booking as no_show and releases the slot.
 */
class HandleNoShowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function handle(NotificationService $notificationService): void
    {
        // Re-check inside a transaction to avoid race conditions
        DB::transaction(function () use ($notificationService) {
            $booking = Booking::lockForUpdate()->find($this->booking->id);

            // If patient arrived, skip
            if ($booking->arrived_at !== null) {
                return;
            }

            // Mark as no-show
            $booking->update(['status' => 'no_show']);

            Log::info('[no_show] Booking marked as no-show', [
                'booking_code' => $booking->booking_code,
                'patient' => $booking->patient_name,
            ]);

            $notificationService->send(
                $booking,
                'no_show',
                "📋 Booking {$booking->booking_code} marked as no-show. Slot released."
            );

            // Phase 4: DELETE booking_slots row → Slot available again for booking
            BookingSlot::where('id', $booking->slot_id)->delete();
        });
    }
}
