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
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->foreignId('duplicate_of_id')
                ->nullable()
                ->after('application_id')
                ->constrained('bug_reports')
                ->nullOnDelete();

            $table->string('resolution')->nullable()->after('status');
            $table->string('environment')->nullable()->after('resolution');

            $table->text('steps_to_reproduce')->nullable()->after('description');
            $table->text('expected_result')->nullable()->after('steps_to_reproduce');
            $table->text('actual_result')->nullable()->after('expected_result');

            $table->string('url', 1000)->nullable()->after('actual_result');
            $table->json('device_metadata')->nullable()->after('url');

            $table->timestamp('resolved_at')->nullable()->after('environment');
            $table->timestamp('closed_at')->nullable()->after('resolved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('duplicate_of_id');
            $table->dropColumn([
                'resolution',
                'environment',
                'steps_to_reproduce',
                'expected_result',
                'actual_result',
                'url',
                'device_metadata',
                'resolved_at',
                'closed_at',
            ]);
        });
    }
};
