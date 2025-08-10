<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\UseCases\Queries\SupportRequestQueryList;

class SupportRequestsController extends Controller
{
    public function list(
        SupportRequestQueryList $query
    )
    {
        $result = $query->handle();

        if ($result->isError) {
            return response()->json([
                'message' => $result->message,
                'errors' => $result->errors,
            ]);
        }

        return response()->json(
            $result->data
        );
    }
}
