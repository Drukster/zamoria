<?php

namespace App\Console\Commands;

use App\Domains\DuaDomainUpdateOrCreate;
use App\DTO\Domains\DuaDomainUpdateOrCreateDTO;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Psr\Log\LoggerInterface;

class ImportListDuasFromJson extends Command
{
    protected $signature = 'import:list-dua';
    protected $description = 'Импорт списка дуа';

    public function handle(
        LoggerInterface $logger
    ): int
    {
        $filePath = storage_path('dua.json');

        if (!File::exists($filePath)) {
            $this->error("Файл {$filePath} не найден.");
            return self::FAILURE;
        }

        try {
            $duas = json_decode(
                json: File::get($filePath),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $ex) {
            $logger->error('Ошибка парсинга JSON: ' . $ex->getMessage(), [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'trace' => $ex->getTraceAsString(),
            ]);
            return self::FAILURE;
        }

        /** @var Collection<string, Category> $categoriesBySlug */
        $categoriesBySlug = Category::all()->keyBy('slug');

        $this->info('⏳ Импортируем дуа...');
        $progress = $this->output->createProgressBar(count($duas));

        $createdCount = 0;

        foreach ($duas as $item) {
            $category = $categoriesBySlug->get($item['category']);

            if (!$category) {
                $this->warn("\n⚠ Категория '{$item['category']}' не найдена (дуа: '{$item['title']}')");
                $progress->advance();
                continue;
            }

            $result = app(DuaDomainUpdateOrCreate::class)
                ->do(
                    DuaDomainUpdateOrCreateDTO::from([
                        'categoryId' => $category->id,
                        'title' => $item['title'],
                        'transliteration' => $item['transliteration'],
                        'translation' => $item['translation'],
                        'is_active' => $item['is_active'] ?? true,
                        'content' => $item['content'],
                    ])
                );

            if ($result->isError) {
                $this->error(
                    "\n❌ Ошибка при создании/обновлении дуа: {$item['title']}"
                );
                continue;
            }

            $createdCount++;
            $progress->advance();
        }

        $progress->finish();
        $this->newLine(2);

        $this->info(
            "✅ Импорт завершён. Создано/обновлено: {$createdCount}, Пропущено: " . (count($duas) - $createdCount)
        );

        return self::SUCCESS;
    }
}
