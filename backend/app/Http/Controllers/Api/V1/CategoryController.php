<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\UseCases\Queries\CategoryQueryList;

class CategoryController extends Controller
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
}
