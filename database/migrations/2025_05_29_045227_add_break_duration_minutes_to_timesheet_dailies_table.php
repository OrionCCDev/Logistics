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
            $table->integer('break_duration_minutes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timesheet_dailies', function (Blueprint $table) {
            $table->dropColumn('break_duration_minutes');
        });
    }
};
