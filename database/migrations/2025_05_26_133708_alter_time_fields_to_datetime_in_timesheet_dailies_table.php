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
        Schema::table('timesheet_dailies', function (Blueprint $table) {
            // Change columns to datetime. Note: Data loss may occur if existing data cannot be converted.
            $table->dateTime('working_start_hour')->nullable()->change();
            $table->dateTime('working_end_hour')->nullable()->change();
            $table->dateTime('break_start_at')->nullable()->change();
            $table->dateTime('break_ends_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timesheet_dailies', function (Blueprint $table) {
            // Revert columns to time. Note: Data loss may occur if datetime data cannot be converted back to time.
            $table->time('working_start_hour')->nullable()->change();
            $table->time('working_end_hour')->nullable()->change();
            $table->time('break_start_at')->nullable()->change();
            $table->time('break_ends_at')->nullable()->change();
        });
    }
};
