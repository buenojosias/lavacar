<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'wallet_id',
        'registered_by_user_id',
        'amount',
        'description',
        'transactionable_type',
        'transactionable_id',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'created_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'R$ ' . number_format($this->amount / 100, 2, ',', '.');
    }
}
