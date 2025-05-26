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
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'start_date')) {
                // If 'start_date' has a foreign key, it needs to be dropped first.
                // Example: $table->dropForeign(['start_date']);
                // Adjust if there is a foreign key constraint on this column.
                $table->dropColumn('start_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // If you need to make this reversible, re-add the column.
            // $table->date('start_date')->nullable(); // Or its original definition
        });
    }
};
