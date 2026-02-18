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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('booking_id')->constrained()->onDelete('cascade');
            $table->string('channel'); // 'whatsapp', 'email'
            $table->string('type');    // 'confirmation', 'reminder', 'no_show'
            $table->text('message');
            $table->string('recipient');
            $table->string('status')->default('sent'); // 'sent', 'failed'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
