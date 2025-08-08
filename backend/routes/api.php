<?php

use App\Http\Controllers\Api\V1\CategoriesController;
use App\Http\Controllers\Api\V1\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('telegram')->group(function () {
        Route::post('/webhook', WebhookController::class);
    });

    Route::prefix('categories')->group(function () {
        Route::controller(CategoriesController::class)->group(function () {
            Route::get('/list', 'list');
            Route::get('/{slug}', 'bySlug');
        });
    });
});
