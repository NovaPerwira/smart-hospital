<?php

namespace App\Console\Commands;

use App\Jobs\SendDailyReportJob;
use App\Services\DailyReportService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * ReportGeneratorCommand — Artisan command: report:daily
 *
 * Scheduled at 21:00 WIB (Asia/Jakarta) daily.
 * 1. Runs aggregation queries via DailyReportService
 * 2. Persists JSON snapshot to daily_reports (updateOrCreate)
 * 3. Dispatches SendDailyReportJob to queue (Telegram send + retry)
 */
class ReportGeneratorCommand extends Command
{
    protected $signature = 'report:daily {--date= : Override date (Y-m-d), defaults to today}';
    protected $description = 'Generate daily KPI report and dispatch to owner Telegram at 21:00 WIB';

    public function handle(DailyReportService $reportService): int
    {
        // Support --date override for manual re-runs / testing
        $dateStr = $this->option('date') ?? now('Asia/Jakarta')->toDateString();
        $date = Carbon::parse($dateStr, 'Asia/Jakarta');

        $this->info("📊 Generating daily report for {$dateStr}...");

        // Step 1 + 2: Run aggregation queries + persist snapshot
        $report = $reportService->generate($date);

        $data = $report->data;
        $this->table(
            ['KPI', 'Value'],
            [
                ['Total Bookings', $data['total_bookings']],
                ['Completed', $data['completed']],
                ['No-Shows', $data['no_show'] . ' (' . $data['no_show_rate'] . '%)'],
                ['Revenue Today', 'Rp ' . number_format($data['revenue_today'], 0, ',', '.')],
                ['Empty Slots Tomorrow', $data['empty_slots_tomorrow']],
            ]
        );

        // Step 3: Dispatch Telegram send job to queue
        SendDailyReportJob::dispatch($report);

        $this->info("✅ Report snapshot saved. Telegram dispatch queued (3 retries with exponential backoff).");

        return self::SUCCESS;
    }
}
