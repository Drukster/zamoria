<?php

namespace App\Repositories;

use App\Models\TelegramUser;

class TelegramUserRepository
{
    public function byTelegramId(
        int $telegram_id
    )
    {
        return TelegramUser::query()
            ->where('telegram_id', $telegram_id)
            ->first();
    }
}
