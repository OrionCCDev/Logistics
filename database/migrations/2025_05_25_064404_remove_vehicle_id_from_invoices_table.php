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
            // Check if the column exists before trying to drop it to avoid errors
            if (Schema::hasColumn('invoices', 'vehicle_id')) {
                // Drop the foreign key constraint first
                // The error message indicated the constraint name is 'invoices_vehicle_id_foreign'
                $table->dropForeign(['vehicle_id']); // Laravel attempts to guess the constraint name if an array is passed
                                                  // Alternatively, pass the exact name: $table->dropForeign('invoices_vehicle_id_foreign');
                $table->dropColumn('vehicle_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // If you need to make this migration truly reversible,
            // you would re-add the column and the foreign key here.
            // $table->unsignedBigInteger('vehicle_id')->nullable()->after('supplier_id'); // Or appropriate position
            // $table->foreign('vehicle_id', 'invoices_vehicle_id_foreign') // You can specify the constraint name
            //       ->references('id')->on('vehicles')
            //       ->onDelete('set null');
        });
    }
};
