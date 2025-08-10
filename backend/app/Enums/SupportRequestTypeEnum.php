<?php

namespace App\Enums;

enum SupportRequestTypeEnum: int
{
    case COMPLAINT = 0;
    case SUGGESTION = 1;
    case QUESTION = 2;

    public function label(): string
    {
        return match ($this) {
            self::COMPLAINT => 'Жалоба',
            self::SUGGESTION => 'Предложение',
            self::QUESTION => 'Вопрос',
        };
    }

    public static function fromValue(
        int $value
    ): ?self
    {
        return self::tryFrom($value);
    }

    public static function valuesWithLabels(): array
    {
        return [
            self::COMPLAINT->value => self::COMPLAINT->label(),
            self::SUGGESTION->value => self::SUGGESTION->label(),
            self::QUESTION->value => self::QUESTION->label(),
        ];
    }
}
