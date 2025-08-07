<?php

namespace App\Http\Middleware;

use App\Domains\AuthDomainCreateTelegramUser;
use App\Repositories\TelegramUserRepository;
use Psr\Log\LoggerInterface;
use SergiX44\Nutgram\Nutgram;

readonly class VerifyTelegramUser
{
    public function __construct(
        private AuthDomainCreateTelegramUser $authDomainCreateTelegramUser,
        private TelegramUserRepository       $telegramUserRepository,
        private LoggerInterface              $logger
    )
    {
    }

    public function __invoke(
        Nutgram $bot,
                $next
    )
    {
        try {
            $user = $bot->user();
            $checkExists = $this->telegramUserRepository
                ->byTelegramId(
                    $user->id
                );

            if (!$checkExists) {
                $data = [
                    'telegram_id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'username' => $user->username,
                    'is_premium' => $user->is_premium ?? false,
                    'is_active' => true
                ];

                $this->authDomainCreateTelegramUser->do(
                    $data
                );
            }

            return $next(
                $bot
            );

        } catch (\Exception $ex) {
            $this->logger->error(
                message: $ex->getMessage(),
                context: [
                    'file' => $ex->getFile(),
                    'line' => $ex->getLine(),
                    'trace' => $ex->getTraceAsString()
                ]
            );

            $bot->sendMessage(
                text: "Произошла ошибка при обработке твоего запроса. Напишите в поддержку"
            );

            throw $ex;
        }
    }
}
