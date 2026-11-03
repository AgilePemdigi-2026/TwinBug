<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('application_id')->nullable()->after('role')->constrained()->nullOnDelete();
        });

        Schema::table('bug_reports', function (Blueprint $table) {
            $table->foreignId('application_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });

        if (! DB::table('users')->exists() && ! DB::table('bug_reports')->exists()) {
            return;
        }

        $applicationId = DB::table('applications')->insertGetId([
            'name' => 'TwinBug Legacy',
            'code' => $this->uniqueCode(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->update(['application_id' => $applicationId]);
        DB::table('bug_reports')->update(['application_id' => $applicationId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('application_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('application_id');
        });

        Schema::dropIfExists('applications');
    }

    private function uniqueCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (DB::table('applications')->where('code', $code)->exists());

        return $code;
    }
};
