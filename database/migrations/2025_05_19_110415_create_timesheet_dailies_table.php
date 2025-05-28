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
        Schema::create('timesheet_dailies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->date('date')->nullable();

            $table->foreignId('project_id')->nullable()->constrained('projects');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');
            $table->dateTime('working_start_hour')->nullable();
            $table->dateTime('working_end_hour')->nullable();
            $table->dateTime('break_start_at')->nullable();
            $table->dateTime('break_ends_at')->nullable();
            $table->decimal('working_hours', 5, 2)->nullable();
            $table->decimal('odometer_start', 10, 2)->nullable();
            $table->decimal('odometer_ends', 10, 2)->nullable();
            $table->decimal('fuel_consumption', 8, 2)->nullable();
            $table->decimal('deduction_amount', 8, 2)->nullable()->default(0);
            $table->text('note')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->nullable()->default('draft');
            $table->enum('fuel_consumption_status', ['by_hours', 'by_odometer'])->nullable()->default('by_odometer');
            $table->timestamps();

            // Unique constraint to prevent duplicate entries for the same user and date
            // Only if both are not null
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheet_dailies');
    }
};
