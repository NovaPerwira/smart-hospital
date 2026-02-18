<?php

namespace App\Console\Commands;

use App\Jobs\HandleNoShowJob;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Phase 4 — Cron: T+2h after appointment slot
 * Queries bookings WHERE booking_date = today AND slot end_time <= (now - 2h) AND arrived_at IS NULL
 * Dispatches HandleNoShowJob for each.
 */
class HandleNoShows extends Command
{
    protected $signature = 'bookings:handle-no-shows';
    protected $description = 'Mark no-show bookings and release their slots (runs T+2h after appointments)';

    public function handle(): int
    {
        $now = Carbon::now();
        $cutoffTime = $now->copy()->subHours(2)->format('H:i:s');
        $today = $now->toDateString();

        // Find bookings that:
        // 1. Were today
        // 2. Slot ended more than 2 hours ago
        // 3. Patient never arrived
        // 4. Not already marked no_show
        $bookings = Booking::with(['slot'])
            ->where('booking_date', $today)
            ->where('status', 'confirmed')
            ->whereNull('arrived_at')
            ->whereHas('slot', function ($q) use ($cutoffTime) {
                $q->where('end_time', '<=', $cutoffTime);
            })
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('No no-show bookings found.');
            return self::SUCCESS;
        }

        $this->info("Processing {$bookings->count()} potential no-show(s)...");

        foreach ($bookings as $booking) {
            HandleNoShowJob::dispatch($booking);
            $this->line("  → Queued no-show handling for {$booking->booking_code} ({$booking->patient_name})");
        }

        return self::SUCCESS;
    }
}
