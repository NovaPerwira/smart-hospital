<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cms_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key');           // e.g. hero_title, hero_subtitle
            $table->string('locale', 5);     // en, id
            $table->string('section', 50);   // hero, about, services, stats, etc.
            $table->text('value');
            $table->string('type', 20)->default('text'); // text, textarea, html
            $table->timestamps();

            $table->unique(['key', 'locale']);
            $table->index(['section', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_contents');
    }
};
