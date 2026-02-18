<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('follow_up_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_id')->constrained()->onDelete('cascade');

            // Trigger event — currently only 'completed' is used per flowchart
            $table->string('trigger_event')->default('completed'); // 'completed'

            // Interval configuration (e.g. 6 months, 3 days, 1 week)
            $table->unsignedInteger('interval_value');
            $table->string('interval_unit'); // 'minutes', 'hours', 'days', 'weeks', 'months'

            // Message template with placeholders: {patient_name}, {doctor_name}, {treatment_name}
            $table->string('channel')->default('whatsapp'); // 'whatsapp', 'email'
            $table->text('message_template');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // One rule per treatment+trigger+interval combination
            $table->index(['treatment_id', 'trigger_event', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_up_rules');
    }
};
