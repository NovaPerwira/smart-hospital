<?php

namespace App\Console\Commands;

use App\Jobs\SendH1ReminderJob;
use App\Models\Booking;
use Illuminate\Console\Command;

/**
 * Phase 2 — Cron: 08:00 Daily
 * Queries bookings WHERE date = tomorrow AND status = 'confirmed' AND reminder_sent_at IS NULL
 * Dispatches SendH1ReminderJob for each.
 */
class SendH1Reminders extends Command
{
    protected $signature = 'bookings:send-h1-reminders';
    protected $description = 'Send H-1 reminder notifications for tomorrow\'s appointments';

    public function handle(): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $bookings = Booking::with(['doctor', 'treatment', 'slot'])
            ->where('booking_date', $tomorrow)
            ->where('status', 'confirmed')
            ->whereNull('reminder_sent_at')
            ->get();

        if ($bookings->isEmpty()) {
            $this->info("No bookings to remind for {$tomorrow}.");
            return self::SUCCESS;
        }

        $this->info("Dispatching H-1 reminders for {$bookings->count()} booking(s) on {$tomorrow}...");

        foreach ($bookings as $booking) {
            SendH1ReminderJob::dispatch($booking);
            $this->line("  → Queued reminder for {$booking->booking_code} ({$booking->patient_name})");
        }

        return self::SUCCESS;
    }
}
