<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope(new CompanyScope);
    }

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
        'notes',
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

    public function statusHistories(): HasMany
    {
        return $this->hasMany(BookingStatusHistory::class);
    }

    public function nextStatus(): ?BookingStatusEnum
    {
        return $this->status->nextStatus();
    }

    public function getFormattedDayAttribute()
    {
        $day = $this->scheduled_date;
        if ($day->isToday()) {
            return 'Hoje';
        } elseif ($day->isTomorrow()) {
            return 'Amanhã';
        } else {
            return $day->format('d/m/Y');
        }
    }

    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price / 100, 2, ',', '.');
    }

    public function getFormattedDurationAttribute()
    {
        $minutes = $this->starts_at->diffInMinutes($this->ends_at);
        if ($minutes < 60) {
            return $minutes . 'min';
        } else if ($minutes % 60 > 0) {
            return floor($minutes / 60) . 'h ' . $minutes % 60 . 'min';
        } else {
            return floor($minutes / 60) . 'h ';
        }
    }
}
