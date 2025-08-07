<?php

namespace App\Domains;

use App\Models\TelegramUser;
use Psr\Log\LoggerInterface;

readonly class AuthDomainCreateTelegramUser
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function do(
        array $properties
    )
    {
        try {
            return TelegramUser::query()
                ->updateOrCreate(
                    [
                        'telegram_id' => $properties['telegram_id'],
                    ],
                    $properties
                );
        } catch (\Exception $ex) {
            $this->logger->error(
                message: $ex->getMessage(),
                context: [
                    'file' => $ex->getFile(),
                    'line' => $ex->getLine(),
                    'trace' => var_export(
                        $ex->getTraceAsString(),
                        true
                    ),
                ]
            );

            throw $ex;
        }
    }
}
