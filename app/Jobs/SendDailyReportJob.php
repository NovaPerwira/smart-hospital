<?php

namespace App\Jobs;

use App\Models\DailyReport;
use App\Services\DailyReportService;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Dispatched by ReportGeneratorCommand.
 * Sends the daily report to Telegram with 3 attempts + exponential backoff.
 * On success: UPDATE daily_reports.telegram_sent_at = NOW()
 * On failure: marks telegram_status = 'failed'
 */
class SendDailyReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Queue retry configuration (per flowchart: 3 attempts, exponential backoff).
     */
    public int $tries = 3;
    public int $maxExceptions = 3;

    /**
     * Exponential backoff: 60s, 120s, 240s
     */
    public function backoff(): array
    {
        return [60, 120, 240];
    }

    public function __construct(public DailyReport $report)
    {
    }

    public function handle(DailyReportService $reportService, TelegramService $telegram): void
    {
        $message = $reportService->formatTelegramMessage($this->report);

        // sendMessage via Bot API — throws on failure (triggers queue retry)
        $telegram->sendMessage($message);

        // UPDATE telegram_sent_at = NOW() on success
        $this->report->update([
            'telegram_sent_at' => now(),
            'telegram_status' => 'sent',
        ]);

        Log::info('[daily_report] Telegram dispatched successfully', [
            'report_date' => \Carbon\Carbon::parse($this->report->report_date)->toDateString(),
        ]);
    }

    /**
     * Called when all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        $this->report->update(['telegram_status' => 'failed']);

        Log::error('[daily_report] Telegram dispatch FAILED after all retries', [
            'report_date' => \Carbon\Carbon::parse($this->report->report_date)->toDateString(),
            'error' => $exception->getMessage(),
        ]);
    }
}
