<?php

namespace App\Models;

use App\Enums\VehicleSizeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'plate',
        'plate_type',
        'brand_model',
        'size',
        'color',
    ];

    protected $casts = [
        'size' => VehicleSizeEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function companyVehicles(): HasMany
    {
        return $this->hasMany(CompanyVehicle::class);
    }
}
