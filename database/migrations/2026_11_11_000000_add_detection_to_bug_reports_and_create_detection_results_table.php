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
        Schema::create('detection_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bug_report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_report_id')->constrained('bug_reports')->cascadeOnDelete();
            $table->unsignedTinyInteger('rank');
            $table->float('text_score', 8, 6);
            $table->string('model');
            $table->timestamps();

            $table->unique(['bug_report_id', 'candidate_report_id']);
            $table->index(['bug_report_id', 'rank']);
        });

        Schema::table('bug_reports', function (Blueprint $table) {
            $table->string('detection_status')->default('pending')->after('status');
            $table->text('detection_error')->nullable()->after('detection_status');
            $table->timestamp('detected_at')->nullable()->after('detection_error');
            $table->float('detection_threshold', 8, 6)->nullable()->after('detected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detection_results');

        Schema::table('bug_reports', function (Blueprint $table) {
            $table->dropColumn([
                'detection_status',
                'detection_error',
                'detected_at',
                'detection_threshold',
            ]);
        });
    }
};
