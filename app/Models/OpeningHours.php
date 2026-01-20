<?php

namespace App\Models;

use App\Enums\WeekdayEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpeningHours extends Model
{
    protected $fillable = [
        'company_id',
        'weekday',
        'opens_at',
        'closes_at',
    ];

    protected $casts = [
        'weekday' => WeekdayEnum::class,
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
