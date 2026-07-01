<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'farm_name',
        'region',
        'crop_type',
        'soil_moisture',
        'soil_pH',
        'temperature_C',
        'rainfall_mm',
        'humidity',
        'NDVI_index',
        'sunlight_hours',
        'pesticide_usage_ml',
        'total_days',
        'cluster',
        'cluster_label',
        'predicted_yield',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($farm) {
            $farm->created_by = auth()->id();
        });

        static::updating(function ($farm) {
            $farm->updated_by = auth()->id();
        });

        static::deleting(function ($farm) {
            if (auth()->check() && !$farm->isForceDeleting()) {
                $farm->deleted_by = auth()->id();
                $farm->saveQuietly();
            }
        });
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }
    public function deleter() { return $this->belongsTo(User::class, 'deleted_by'); }
}
