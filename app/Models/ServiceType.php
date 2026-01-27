<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model
{
    use SoftDeletes;

    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope(new CompanyScope);
    }

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'is_active',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ServiceVariant::class);
    }
}
