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
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->default('dashAssets/uploads/operators/default_operator_image.png');
            $table->string('front_license_image')->nullable();
            $table->string('back_license_image')->nullable();
            $table->string('status')->default('active');
            $table->string('license_number')->nullable();;
            $table->date('license_expiry_date')->nullable();;
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operators');
    }
};
