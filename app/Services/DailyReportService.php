<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\DailyReport;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Daily Report Service
 *
 * Runs 4 aggregation queries against today's data, persists a JSON snapshot
 * to daily_reports, and formats the Telegram message.
 */
class DailyReportService
{
    /**
     * Compute all KPIs for a given date, persist snapshot, return the report.
     */
    public function generate(Carbon $date): DailyReport
    {
        $dateStr = $date->toDateString(); // e.g. '2026-02-18'

        // ─────────────────────────────────────────────────────────────
        // AGGREGATION QUERY 1: COUNT bookings by status (WHERE date=today)
        // ─────────────────────────────────────────────────────────────
        $bookingsByStatus = Booking::where('booking_date', $dateStr)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalBookings = array_sum($bookingsByStatus);
        $confirmedCount = $bookingsByStatus['confirmed'] ?? 0;
        $completedCount = $bookingsByStatus['completed'] ?? 0;
        $cancelledCount = $bookingsByStatus['cancelled'] ?? 0;
        $noShowCount = $bookingsByStatus['no_show'] ?? 0;

        // ─────────────────────────────────────────────────────────────
        // AGGREGATION QUERY 2: SUM payments.amount WHERE paid_at LIKE 'today%'
        // ─────────────────────────────────────────────────────────────
        $revenueToday = Payment::whereDate('paid_at', $dateStr)
            ->where('status', 'paid')
            ->sum('amount');

        // ─────────────────────────────────────────────────────────────
        // AGGREGATION QUERY 3: COUNT empty slots tomorrow
        // Generate all possible slots for tomorrow, subtract booked ones
        // ─────────────────────────────────────────────────────────────
        $tomorrow = $date->copy()->addDay()->toDateString();
        $totalSlots = $this->countPossibleSlots(); // e.g. 9:00–17:00 @ 30min = 16
        $bookedTomorrow = BookingSlot::where('date', $tomorrow)->count();
        $emptySlotsCount = max(0, $totalSlots - $bookedTomorrow);

        // ─────────────────────────────────────────────────────────────
        // AGGREGATION QUERY 4: Revenue by treatment type
        // JOIN treatments GROUP BY category
        // ─────────────────────────────────────────────────────────────
        $revenueByTreatment = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('treatments', 'bookings.treatment_id', '=', 'treatments.id')
            ->whereDate('payments.paid_at', $dateStr)
            ->where('payments.status', 'paid')
            ->select('treatments.name', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('treatments.name')
            ->orderByDesc('total')
            ->get()
            ->map(fn($r) => ['treatment' => $r->name, 'total' => (float) $r->total])
            ->toArray();

        // ─────────────────────────────────────────────────────────────
        // PERSIST SNAPSHOT → updateOrCreate daily_reports
        // ─────────────────────────────────────────────────────────────
        $data = [
            'date' => $dateStr,
            'total_bookings' => $totalBookings,
            'bookings_by_status' => $bookingsByStatus,
            'confirmed' => $confirmedCount,
            'completed' => $completedCount,
            'cancelled' => $cancelledCount,
            'no_show' => $noShowCount,
            'no_show_rate' => $totalBookings > 0
                ? round(($noShowCount / $totalBookings) * 100, 1)
                : 0,
            'revenue_today' => (float) $revenueToday,
            'revenue_by_treatment' => $revenueByTreatment,
            'empty_slots_tomorrow' => $emptySlotsCount,
            'booked_slots_tomorrow' => $bookedTomorrow,
            'generated_at' => now()->toIso8601String(),
        ];

        $report = DailyReport::updateOrCreate(
            ['report_date' => $dateStr],
            ['data' => $data, 'telegram_status' => 'pending']
        );

        Log::info('[daily_report] Snapshot persisted', [
            'date' => $dateStr,
            'total_bookings' => $totalBookings,
            'revenue' => $revenueToday,
        ]);

        return $report;
    }

    /**
     * Format the Telegram message with emoji headers.
     */
    public function formatTelegramMessage(DailyReport $report): string
    {
        $d = $report->data;
        $date = Carbon::parse($d['date'])->format('d M Y');
        $rev = number_format($d['revenue_today'], 0, ',', '.');

        // Revenue by treatment breakdown
        $treatmentLines = '';
        foreach ($d['revenue_by_treatment'] as $item) {
            $t = number_format($item['total'], 0, ',', '.');
            $treatmentLines .= "  • {$item['treatment']}: Rp {$t}\n";
        }
        if (empty($treatmentLines)) {
            $treatmentLines = "  • (no revenue today)\n";
        }

        return <<<MSG
        📊 *Daily Clinic Report — {$date}*
        ━━━━━━━━━━━━━━━━━━━━━━━━

        📅 *Bookings Today*
          ✅ Confirmed : {$d['confirmed']}
          ✔️ Completed : {$d['completed']}
          ❌ Cancelled : {$d['cancelled']}
          👻 No-Show   : {$d['no_show']} ({$d['no_show_rate']}%)
          📋 Total     : {$d['total_bookings']}

        💰 *Revenue Today*
          Total: Rp {$rev}
        {$treatmentLines}
        🗓️ *Tomorrow's Availability*
          🟢 Empty Slots : {$d['empty_slots_tomorrow']}
          🔴 Booked Slots: {$d['booked_slots_tomorrow']}

        ━━━━━━━━━━━━━━━━━━━━━━━━
        🤖 _Auto-generated at 21:00 WIB_
        MSG;
    }

    /**
     * Total possible appointment slots per day (09:00–17:00 @ 30 min intervals).
     */
    private function countPossibleSlots(): int
    {
        // 9:00 to 17:00 = 8 hours = 16 slots of 30 minutes
        return (int) ((17 - 9) * 60 / 30);
    }
}
