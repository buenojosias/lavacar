<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cnpj',
        'address',
        'district',
        'zipcode',
        'latitude',
        'longitude',
        'city_id',
        'logo',
        'whatsapp',
        'simultaneous_services',
        'rating_avg',
        'rating_count',
        'is_active',
        'is_visible',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function companyVehicles(): HasMany
    {
        return $this->hasMany(CompanyVehicle::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function hours(): HasMany
    {
        return $this->hasMany(OpeningHours::class);
    }

    public function serviceTypes(): HasMany
    {
        return $this->hasMany(ServiceType::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function getFormatedWhatsappAttribute(): string
    {

        $whatsapp = preg_replace('/\D/', '', $this->whatsapp);
        $whatsapp = preg_replace('/^55/', '', $whatsapp);

        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $whatsapp);
    }

    public function getFormatedCnpjAttribute(): string
    {
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $this->cnpj);
    }
}
