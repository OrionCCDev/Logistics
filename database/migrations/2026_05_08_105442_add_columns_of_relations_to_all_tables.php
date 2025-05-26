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
        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
        if (Schema::hasTable('branches')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->foreignId('country_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
        if (Schema::hasTable('projects')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
        if (Schema::hasTable('employee_projects')) {
            Schema::table('employee_projects', function (Blueprint $table) {
                if (!Schema::hasColumn('employee_projects', 'employee_id')) {
                    $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade');
                }
                if (!Schema::hasColumn('employee_projects', 'project_id')) {
                    $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
                }
            });
        }
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->foreignId('category_id')->default(1)->constrained()->onDelete('cascade');
            });
        }
        if (Schema::hasTable('operators')) {
            Schema::table('operators', function (Blueprint $table) {
                $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade');
                $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('cascade');
            });
        }
        if (Schema::hasTable('vehicles')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade');
                $table->foreignId('operator_id')->nullable()->constrained('operators')->onDelete('cascade');
            });
        }
        if (Schema::hasTable('project_vehicles')) {
            Schema::table('project_vehicles', function (Blueprint $table) {
                // Columns project_id and vehicle_id are already created in the initial migration.
                // This migration should only ensure foreign keys if they were not added,
                // or if specific characteristics like ->nullable() need to be enforced later.
                // However, the initial migration already sets them as constrained foreign keys.
                // Thus, these lines are redundant if the goal was to add the columns.
                // If the goal was to modify them (e.g., make nullable), that would be different.
                // For now, assuming redundancy in adding columns already present.
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                if (Schema::hasColumn('employees', 'branch_id')) {
                    $table->dropForeign(['branch_id']);
                }
                if (Schema::hasColumn('employees', 'user_id')) {
                    $table->dropForeign(['user_id']);
                }
            });
        }
        if (Schema::hasTable('branches')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->dropForeign(['country_id']);
            });
        }
        if (Schema::hasTable('projects')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
            });
        }
        if (Schema::hasTable('employee_projects')) {
            Schema::table('employee_projects', function (Blueprint $table) {
                if (Schema::hasColumn('employee_projects', 'employee_id')) {
                    $table->dropForeign(['employee_id']);
                }
                if (Schema::hasColumn('employee_projects', 'project_id')) {
                    $table->dropForeign(['project_id']);
                }
            });
        }
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
            });
        }
        if (Schema::hasTable('operators')) {
            Schema::table('operators', function (Blueprint $table) {
                if (Schema::hasColumn('operators', 'supplier_id')) {
                    $table->dropForeign(['supplier_id']);
                }
                if (Schema::hasColumn('operators', 'vehicle_id')) {
                    $table->dropForeign('operators_vehicle_id_foreign');
                }
            });
        }
        if (Schema::hasTable('vehicles')) {
            Schema::table('vehicles', function (Blueprint $table) {
                if (Schema::hasColumn('vehicles', 'supplier_id')) {
                    $table->dropForeign(['supplier_id']);
                }
                if (Schema::hasColumn('vehicles', 'operator_id')) {
                    $table->dropForeign('vehicles_operator_id_foreign');
                }
            });
        }
        if (Schema::hasTable('project_vehicles')) {
            Schema::table('project_vehicles', function (Blueprint $table) {
                // Columns project_id and vehicle_id are already created in the initial migration.
                // This migration should only ensure foreign keys if they were not added,
                // or if specific characteristics like ->nullable() need to be enforced later.
                // However, the initial migration already sets them as constrained foreign keys.
                // Thus, these lines are redundant if the goal was to add the columns.
                // If the goal was to modify them (e.g., make nullable), that would be different.
                // For now, assuming redundancy in adding columns already present.
            });
        }

        Schema::enableForeignKeyConstraints();
    }
};
