<?php

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case CASH = 'cash';
    case PIX = 'pix';
    case DEBIT_CARD = 'debit_card';
    case CREDIT_CARD = 'credit_card';

    public function label()
    {
        return match ($this) {
            self::CASH => 'Dinheiro',
            self::PIX => 'Pix',
            self::DEBIT_CARD => 'Cartão de débito',
            self::CREDIT_CARD => 'Cartão de crédito',
        };
    }
}
