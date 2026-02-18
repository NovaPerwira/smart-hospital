<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withProviders([App\Providers\EventServiceProvider::class])
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        // Phase 2: H-1 Reminder — 08:00 Daily
        $schedule->command('bookings:send-h1-reminders')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scheduler.log'));

        // Phase 4: No-Show — every hour
        $schedule->command('bookings:handle-no-shows')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scheduler.log'));

        // Follow-Up Dispatch Loop — every 5 minutes (per flowchart)
        $schedule->command('followups:dispatch')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scheduler.log'));

        // Daily Owner Report — 21:00 WIB (Asia/Jakarta)
        $schedule->command('report:daily')
            ->dailyAt('21:00')
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scheduler.log'));
    })
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

