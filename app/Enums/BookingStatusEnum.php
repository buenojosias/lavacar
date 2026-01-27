<?php

namespace App\Enums;

enum BookingStatusEnum: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CHECKED_IN = 'checked_in';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case PICKED_UP = 'picked_up';
    case RESCHEDULED = 'rescheduled';
    case NO_SHOW = 'no_show';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::CONFIRMED => 'Confirmado',
            self::CHECKED_IN => 'Check-in',
            self::IN_PROGRESS => 'Em andamento',
            self::COMPLETED => 'Concluído',
            self::PICKED_UP => 'Retirado',
            self::RESCHEDULED => 'Reagendado',
            self::NO_SHOW => 'Não compareceu',
            self::CANCELLED => 'Cancelado',
        };
    }

    public function actionLabel() {
        return match ($this) {
            self::PENDING => null,
            self::CONFIRMED => 'Confirmar',
            self::CHECKED_IN => 'Registrar entrada',
            self::IN_PROGRESS => 'Iniciar serviço',
            self::COMPLETED => 'Marcar como concluído',
            self::PICKED_UP => 'Registrar chegada',
            self::RESCHEDULED => 'Reagendar',
            self::NO_SHOW => 'Marcar como no show',
            self::CANCELLED => 'Cancelar',
        };
    }

    public function nextStatus(): ?self
    {
        return match ($this) {
            self::PENDING => self::CONFIRMED,
            self::CONFIRMED => self::CHECKED_IN,
            self::CHECKED_IN => self::IN_PROGRESS,
            self::IN_PROGRESS => self::COMPLETED,
            self::COMPLETED => self::PICKED_UP,
            self::PICKED_UP => null,
            self::RESCHEDULED => null,
            self::NO_SHOW => null,
            self::CANCELLED => null,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'orange',
            self::CONFIRMED => 'blue',
            self::CHECKED_IN => 'sky',
            self::IN_PROGRESS => 'neutral',
            self::COMPLETED => 'green',
            self::PICKED_UP => 'black',
            self::RESCHEDULED => 'gray',
            self::NO_SHOW => 'red',
            self::CANCELLED => 'red',
        };
    }
}
