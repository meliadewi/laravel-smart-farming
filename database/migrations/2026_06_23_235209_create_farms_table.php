<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->string('farm_name');
            $table->string('region');
            $table->string('crop_type');
            $table->float('soil_moisture');
            $table->float('soil_pH');
            $table->float('temperature_C');
            $table->float('rainfall_mm');
            $table->float('humidity');
            $table->float('NDVI_index');
            $table->float('sunlight_hours');
            $table->float('pesticide_usage_ml');
            $table->integer('total_days')->default(120);
            $table->integer('cluster')->nullable();
            $table->string('cluster_label')->nullable();
            $table->float('predicted_yield')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('farms');
    }
};
