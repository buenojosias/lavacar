<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceVariant extends Model
{
    protected $fillable = [
        'service_type_id',
        'vehicle_size',
        'duration',
        'price',
        'is_active',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price / 100, 2, ',', '.');
    }

    public function getFormattedDurationAttribute()
    {
        $minutes = $this->duration;
        if ($minutes < 60) {
            return $minutes . 'min';
        } else if ($minutes % 60 > 0) {
            return floor($minutes / 60) . 'h ' . $minutes % 60 . 'min';
        } else {
            return floor($minutes / 60) . 'h ';
        }
    }
}
