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
        Schema::table('users', function (Blueprint $table): void {
            $table->string('language', 5)->default('en')->after('role');
            $table->string('timezone', 64)->default('Asia/Jakarta')->after('language');
            $table->string('date_format', 32)->default('DD MMM YYYY')->after('timezone');
            $table->string('theme', 16)->default('light')->after('date_format');
            $table->json('notification_preferences')->nullable()->after('theme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'language',
                'timezone',
                'date_format',
                'theme',
                'notification_preferences',
            ]);
        });
    }
};
