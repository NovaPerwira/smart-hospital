<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('booking_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('method')->default('cash'); // 'cash', 'transfer', 'qris'
            $table->string('status')->default('paid'); // 'paid', 'pending', 'refunded'
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index for daily revenue aggregation: WHERE paid_at LIKE 'today%'
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
