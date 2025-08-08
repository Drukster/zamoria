<?php

namespace App\UseCases\Queries;

use App\Models\Category;
use App\Utils\OperationResult;
use Illuminate\Database\Eloquent\Collection;

class CategoryQueryList
{
    public function handle(): OperationResult
    {
        $list = Category::query()->get();

        if ($list->isEmpty()) {
            return OperationResult::error(
                message: 'Список категорий пуст'
            );
        }

        $result = $this->transform(
            $list
        );

        return OperationResult::success(
            $result
        );
    }

    private function transform(
        Collection $data
    ): Collection|\Illuminate\Support\Collection
    {
        return $data->map(function (Category $record) {
            return [
                'id' => $record->id,
                'title' => $record->title,
                'slug' => $record->slug,
            ];
        });
    }
}
