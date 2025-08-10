<?php

namespace App\UseCases\Queries;

use App\Models\SupportRequest;
use App\Utils\OperationResult;
use Illuminate\Database\Eloquent\Collection;

class SupportRequestQueryList
{
    public function handle(): OperationResult
    {
        $list = SupportRequest::query()->get();

        if ($list->isEmpty()) {
            return OperationResult::error(
                message: 'Список пуст'
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
        return $data->map(function (SupportRequest $record) {
            return [
                'id' => $record->id,
                'message' => $record->message,
                'type' => $record->type,
                'status' => $record->status,
                'created_at' => $record->created_at->format('d.m.Y'),
            ];
        });
    }
}
