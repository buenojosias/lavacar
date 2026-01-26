<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'customer_id',
        'company_vehicle_id',
        'service_variant_id',
        'scheduled_date',
        'starts_at',
        'ends_at',
        'price',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'status' => BookingStatusEnum::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function companyVehicle(): BelongsTo
    {
        return $this->belongsTo(CompanyVehicle::class);
    }

    public function serviceVariant(): BelongsTo
    {
        return $this->belongsTo(ServiceVariant::class);
    }

    public function nextStatus(): ?BookingStatusEnum
    {
        return $this->status->nextStatus();
    }
}
