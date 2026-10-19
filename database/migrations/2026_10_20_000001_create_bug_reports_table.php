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
        Schema::create('bug_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('module')->nullable();
            $table->text('description');
            $table->string('severity')->default('medium');
            $table->string('status')->default('open');
            $table->string('screenshot_path')->nullable();
            $table->string('screenshot_name')->nullable();
            $table->unsignedBigInteger('screenshot_size')->nullable();
            $table->string('screenshot_dimensions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_reports');
    }
};
