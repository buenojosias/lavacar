<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope(new CompanyScope);
    }

    protected $fillable = [
        'company_id',
        'name',
        'balance',
    ];

    protected $casts = [
        'balance' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getFormattedBalanceAttribute()
    {
        return 'R$ ' . number_format($this->balance / 100, 2, ',', '.');
    }
}
