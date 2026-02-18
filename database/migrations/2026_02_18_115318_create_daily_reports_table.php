<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->unique(); // One snapshot per day

            // JSON blob of all KPIs computed that day
            $table->json('data');

            // Telegram dispatch tracking
            $table->timestamp('telegram_sent_at')->nullable();
            $table->string('telegram_status')->default('pending'); // 'pending', 'sent', 'failed'

            $table->timestamps();

            $table->index('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
