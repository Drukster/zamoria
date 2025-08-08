<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\Queries\CategoryQueryBySlugDTO;
use App\Http\Controllers\Controller;
use App\UseCases\Queries\CategoryQueryBySlug;
use App\UseCases\Queries\CategoryQueryList;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function list(
        CategoryQueryList $query
    )
    {
        $result = $query->handle();

        if ($result->isError) {
            return response()->json([
                'message' => $result->message,
                'errors' => $result->errors
            ]);
        }

        return response()->json(
            $result->data
        );
    }

    public function bySlug(
        string              $slug,
        CategoryQueryBySlug $query
    )
    {
        $result = $query->handle(
            CategoryQueryBySlugDTO::from([
                'slug' => $slug,
            ])
        );

        if ($result->isError) {
            return response()->json([
                'message' => $result->message,
                'errors' => $result->errors
            ]);
        }

        return response()->json(
            $result->data
        );
    }
}
