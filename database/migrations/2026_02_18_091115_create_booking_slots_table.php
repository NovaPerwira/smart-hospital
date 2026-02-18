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
        Schema::create('booking_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('chair_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            // Layer 3: Database Constraints
            // A doctor cannot be booked twice for the same time slot
            $table->unique(['doctor_id', 'date', 'start_time', 'end_time'], 'doctor_slot_unique');

            // A chair cannot be booked twice for the same time slot
            $table->unique(['chair_id', 'date', 'start_time', 'end_time'], 'chair_slot_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_slots');
    }
};
