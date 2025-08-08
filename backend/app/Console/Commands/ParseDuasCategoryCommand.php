<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Services\Dua\DuaService;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class ParseDuasCategoryCommand extends Command
{
    protected $signature = 'category:parse';
    protected $description = 'Парсинг категорий дуа с сайта Академия Медина';

    public function handle(
        DuaService      $duaService,
        LoggerInterface $logger
    ): int
    {
        $this->info('Парсинг начат...');

        try {
            $duas = $duaService->syncCategories();

            foreach ($duas as $dua) {
                $category = Category::query()->updateOrCreate(
                    ['slug' => $dua['slug']],
                    [
                        'title' => $dua['title'],
                    ]
                );

                $this->line("✔ Категория сохранена: {$category->title} (slug: {$category->slug})");
            }

            $this->info('Парсинг завершён.');

            return self::SUCCESS;
        } catch (\Exception $ex) {
            $logger->error(
                message: $ex->getMessage(),
                context: [
                    'file' => $ex->getFile(),
                    'line' => $ex->getLine(),
                    'trace' => var_export(
                        $ex->getTrace(),
                        true
                    )
                ]
            );

            $this->error('Произошла ошибка при создании категории!');

            return self::FAILURE;
        }
    }
}
