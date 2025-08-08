<?php

namespace App\Services\Dua;

use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

readonly class DuaService
{
    public function __construct(
        private DuaHttpClient   $duaHttpClient,
        private LoggerInterface $logger
    ){
    }

    public function syncCategories(): array
    {
        try {
            $response = $this->duaHttpClient->get();
            if (!$response) {
                $this->logger->alert(
                    "Не удалось загрузить страницу с категориями дуа!",
                );
            }

            $crawler = new Crawler($response);

            $result = [];

            $crawler
                ->filter('div#catalog.catalog.catalog-library a.green-title')
                ->each(function (Crawler $node) use (&$result) {
                    $text = $node->text();
                    $href = $node->attr('href');
                    $slug = basename($href);

                    $result[] = [
                        'title' => $text,
                        'slug' => $slug,
                    ];
                });

            return $result;

        } catch (\Exception $ex) {
            $this->logger->error(
                message: $ex->getMessage(),
                context: [
                    'file' => $ex->getFile(),
                    'line' => $ex->getLine(),
                    'trace' => $ex->getTraceAsString(),
                ]
            );

            return [];
        }
    }
}
