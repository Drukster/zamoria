<?php

namespace App\Domains;

use App\DTO\Domains\DuaDomainUpdateOrCreateDTO;
use App\Models\Dua;
use App\Utils\OperationResult;
use Psr\Log\LoggerInterface;

readonly class DuaDomainUpdateOrCreate
{
    public function __construct(
        private LoggerInterface $logger,
    )
    {
    }

    public function do(
        DuaDomainUpdateOrCreateDTO $data
    ): OperationResult
    {
        try {
            $result = Dua::query()
                ->updateOrCreate(
                    ['title' => $data->title],
                    $data->toArrayAsSnakeCase()
                );

            return OperationResult::success(
                $result
            );
        } catch (\Exception $ex) {
            $this->logger->error(
                message: $ex->getMessage(),
                context: [
                    'file' => $ex->getFile(),
                    'line' => $ex->getLine(),
                    'trace' => $ex->getTraceAsString(),
                ]
            );

            return OperationResult::error(
                message: 'Произошла непредвиденная ошибка'
            );
        }
    }
}
