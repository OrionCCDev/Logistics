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
        Schema::table('project_vehicle', function (Blueprint $table) {
            if (!Schema::hasColumn('project_vehicle', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('project_vehicle', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('project_vehicle')) {
            Schema::table('project_vehicle', function (Blueprint $table) {
                if (Schema::hasColumn('project_vehicle', 'notes')) {
                    $table->dropColumn('notes');
                }
                if (Schema::hasColumn('project_vehicle', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};
