<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bug_report_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bug_report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('matched_report_id')->constrained('bug_reports')->cascadeOnDelete();
            $table->decimal('text_similarity', 5, 2);
            $table->decimal('image_similarity', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['bug_report_id', 'matched_report_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_report_matches');
    }
};
