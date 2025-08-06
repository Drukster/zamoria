<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use SergiX44\Nutgram\Nutgram;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class WebhookController extends Controller
{
    public function __construct(
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run(
        Nutgram $bot
    ): JsonResponse
    {
        try {
            $bot->run();

            return response()->json(
                data: ['message' => 'Гуд'],
                status: ResponseAlias::HTTP_OK
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

            return response()->json(
                data: ['message' => 'Ноу гуд',],
                status: ResponseAlias::HTTP_BAD_REQUEST

            );
        }
    }
}
