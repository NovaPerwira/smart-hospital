<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scheduled_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('follow_up_rule_id')->constrained()->onDelete('cascade');

            // When to dispatch the notification
            $table->timestamp('dispatch_at');

            // Compiled message (template already resolved with patient/doctor names)
            $table->text('message');
            $table->string('channel')->default('whatsapp');
            $table->string('recipient'); // phone number or email

            // Lifecycle: pending → sent | failed
            $table->string('status')->default('pending'); // 'pending', 'sent', 'failed'
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            // Index for the queue worker poll query
            $table->index(['status', 'dispatch_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_follow_ups');
    }
};
