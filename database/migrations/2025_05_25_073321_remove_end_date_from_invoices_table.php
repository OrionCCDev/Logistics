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
            if (Schema::hasColumn('invoices', 'end_date')) {
                // If 'end_date' has a foreign key, it needs to be dropped first.
                // Example: $table->dropForeign(['end_date']);
                // Adjust if there is a foreign key constraint on this column.
                $table->dropColumn('end_date');
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
            // $table->date('end_date')->nullable(); // Or its original definition
        });
    }
};
