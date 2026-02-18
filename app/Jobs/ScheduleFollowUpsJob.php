<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\FollowUpRule;
use App\Models\ScheduledFollowUp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Follow-Up Resolver Job
 *
 * Queries follow_up_rules WHERE treatment_id = ? AND is_active = 1
 * For each rule:
 *   1. Calculate dispatch_at = completed_at + interval_value interval_unit
 *   2. Compile message template with {patient_name}, {doctor_name}, etc.
 *   3. INSERT into scheduled_follow_ups (status=pending)
 */
class ScheduleFollowUpsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function handle(): void
    {
        $booking = $this->booking->load(['doctor', 'treatment']);

        // Query follow_up_rules WHERE treatment_id=? AND is_active=1 AND trigger_event='completed'
        $rules = FollowUpRule::where('treatment_id', $booking->treatment_id)
            ->where('trigger_event', 'completed')
            ->where('is_active', true)
            ->get();

        if ($rules->isEmpty()) {
            Log::info('[follow_up] No active rules found — skipping', [
                'booking_code' => $booking->booking_code,
                'treatment' => $booking->treatment->name,
            ]);
            return; // Skip — No Follow-Up (per flowchart)
        }

        $completedAt = $booking->completed_at ?? now();

        // FOR EACH ACTIVE RULE → CREATE FOLLOW-UP JOB
        /** @var \App\Models\FollowUpRule $rule */
        foreach ($rules as $rule) {
            // Step 1: Calculate dispatch_at
            $dispatchAt = $rule->calculateDispatchAt($completedAt);

            // Step 2: Compile message template
            $message = $rule->compileMessage($booking);

            // Step 3: INSERT scheduled_follow_ups
            ScheduledFollowUp::create([
                'booking_id' => $booking->id,
                'follow_up_rule_id' => $rule->id,
                'dispatch_at' => $dispatchAt,
                'message' => $message,
                'channel' => $rule->channel,
                'recipient' => $booking->patient_phone,
                'status' => 'pending',
            ]);

            Log::info('[follow_up] Scheduled follow-up', [
                'booking_code' => $booking->booking_code,
                'rule_id' => $rule->id,
                'dispatch_at' => $dispatchAt->toDateTimeString(),
                'channel' => $rule->channel,
            ]);
        }
    }
}
