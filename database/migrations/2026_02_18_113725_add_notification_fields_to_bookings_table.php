<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Phase 2: H-1 Reminder tracking
            $table->timestamp('reminder_sent_at')->nullable()->after('status');

            // Phase 3: Patient response handling
            $table->string('confirmation_token')->nullable()->unique()->after('reminder_sent_at');
            $table->timestamp('confirmed_at')->nullable()->after('confirmation_token');
            $table->boolean('cancellation_pending')->default(false)->after('confirmed_at');

            // Phase 4: No-show handling
            $table->timestamp('arrived_at')->nullable()->after('cancellation_pending');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'reminder_sent_at',
                'confirmation_token',
                'confirmed_at',
                'cancellation_pending',
                'arrived_at',
            ]);
        });
    }
};
