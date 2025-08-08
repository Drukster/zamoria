<?php

namespace App\Enums;

enum SupportRequestStatusEnum: int
{
    case NEW = 0;
    case CLOSED = 1;

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Новый',
            self::CLOSED => 'Закрыт',
        };
    }

    public static function valuesWithLabels(): array
    {
        return [
            self::NEW->value => self::NEW->label(),
            self::CLOSED->value => self::CLOSED->label(),
        ];
    }
}
