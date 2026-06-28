<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model {
    protected $fillable = [
        'farm_name', 'region', 'crop_type',
        'soil_moisture', 'soil_pH', 'temperature_C',
        'rainfall_mm', 'humidity', 'NDVI_index',
        'sunlight_hours', 'pesticide_usage_ml', 'total_days',
        'cluster', 'cluster_label', 'predicted_yield'
    ];
}
