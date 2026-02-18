<?php

namespace App\Console\Commands;

use App\Jobs\DispatchFollowUpJob;
use App\Models\ScheduledFollowUp;
use Illuminate\Console\Command;

/**
 * Queue Worker — Dispatch Loop (every 5 minutes per flowchart)
 *
 * Polls: scheduled_follow_ups WHERE dispatch_at <= NOW() AND status = 'pending'
 * Dispatches DispatchFollowUpJob for each due follow-up.
 */
class DispatchFollowUps extends Command
{
    protected $signature = 'followups:dispatch';
    protected $description = 'Dispatch all pending follow-up notifications that are due';

    public function handle(): int
    {
        $due = ScheduledFollowUp::due()->with('booking')->get();

        if ($due->isEmpty()) {
            $this->info('No follow-ups due at this time.');
            return self::SUCCESS;
        }

        $this->info("Dispatching {$due->count()} follow-up(s)...");

        foreach ($due as $followUp) {
            DispatchFollowUpJob::dispatch($followUp);
            $this->line("  → Queued follow-up #{$followUp->id} for booking {$followUp->booking->booking_code} → {$followUp->recipient}");
        }

        return self::SUCCESS;
    }
}
