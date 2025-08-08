<?php

namespace App\UseCases\Queries;

use App\DTO\Queries\CategoryQueryBySlugDTO;
use App\Models\Category;
use App\Models\Dua;
use App\Utils\OperationResult;
use Illuminate\Support\Facades\Validator;

class CategoryQueryBySlug
{
    public function handle(
        CategoryQueryBySlugDTO $data
    ): OperationResult
    {
        $v = Validator::make(
            $data->toArray(),
            [
                'slug' => ['required', 'string', 'exists:categories,slug'],
            ]
        );

        if ($v->fails()) {
            return OperationResult::error(
                message: 'Произошла ошибка валидации',
                errors: $v->errors()->toArray()
            );
        }

        $record = Category::query()
            ->where('slug', $data->slug)
            ->with(['duas'])
            ->first();

        $result = $this->transform(
            $record
        );

        return OperationResult::success(
            $result
        );
    }

    private function transform(
        Category $record
    ): array
    {
        return [
            'id' => $record->id,
            'title' => $record->title,
            'slug' => $record->slug,
            'duas' => $record->duas->map(function (Dua $record) {
                return [
                    'id' => $record->id,
                    'title' => $record->title,
                    'content' => $record->content,
                    'arabicText' => $record->arabic_text,
                    'translation' => $record->translation,
                    'transliteration' => $record->transliteration,
                ];
            })
        ];
    }
}
