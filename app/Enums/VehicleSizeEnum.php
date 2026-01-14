<?php

namespace App\Enums;

enum VehicleSizeEnum: string
{
    case MOTORCYCLE = 'MC';
    case SMALL = 'SM';
    case MEDIUM = 'MD';
    case LARGE = 'LG';
    case EXTRA_LARGE = 'XL';

    public function label(): string
    {
        return match ($this) {
            self::MOTORCYCLE => 'Motocicleta',
            self::SMALL => 'Pequeno',
            self::MEDIUM => 'Médio',
            self::LARGE => 'Grande',
            self::EXTRA_LARGE => 'Extra Grande',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::MOTORCYCLE => 'Motocicletas',
            self::SMALL => 'Coupés, sedãs pequenos, hatchbacks',
            self::MEDIUM => 'Sedãs médios/grandes, SUVs pequenos, crossovers',
            self::LARGE => 'SUVs grandes, SUVs com 3ª fileira de assentos, caminhonetes leves',
            self::EXTRA_LARGE => 'Vans, minivans, caminhonetes pesadas (heavy duty pickups), veículos muito altos',
        };
    }
}
