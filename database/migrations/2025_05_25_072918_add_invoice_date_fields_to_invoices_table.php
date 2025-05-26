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
            // Add submission_date if it doesn't exist
            if (!Schema::hasColumn('invoices', 'submission_date')) {
                $table->date('submission_date')->after('invoice_number'); // Adjust ->after() as needed for desired column order
            }
            // Add invoice_from_date if it doesn't exist
            if (!Schema::hasColumn('invoices', 'invoice_from_date')) {
                $table->date('invoice_from_date')->after('submission_date');
            }
            // Add invoice_to_date if it doesn't exist
            if (!Schema::hasColumn('invoices', 'invoice_to_date')) {
                $table->date('invoice_to_date')->after('invoice_from_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop columns if they exist (to make it reversible)
            if (Schema::hasColumn('invoices', 'invoice_to_date')) {
                $table->dropColumn('invoice_to_date');
            }
            if (Schema::hasColumn('invoices', 'invoice_from_date')) {
                $table->dropColumn('invoice_from_date');
            }
            if (Schema::hasColumn('invoices', 'submission_date')) {
                $table->dropColumn('submission_date');
            }
        });
    }
};
