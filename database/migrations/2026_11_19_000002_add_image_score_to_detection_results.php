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
        Schema::table('detection_results', function (Blueprint $table) {
            $table->decimal('image_score', 5, 4)->nullable()->after('text_score');
            $table->decimal('text_score', 5, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detection_results', function (Blueprint $table) {
            $table->dropColumn('image_score');
            $table->decimal('text_score', 8, 6)->nullable(false)->change();
        });
    }
};
