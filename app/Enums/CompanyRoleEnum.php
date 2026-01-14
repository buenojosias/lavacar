<?php

namespace App\Enums;

enum CompanyRoleEnum: string
{
    case OWNER = 'OWNER';
    case MANAGER = 'MANAGER';
    case EMPLOYEE = 'EMPLOYEE';

    public function label(): string
    {
        return match ($this) {
            self::OWNER => 'Proprietário(a)',
            self::MANAGER => 'Gerente',
            self::EMPLOYEE => 'Funcionário(a)',
        };
    }
}
