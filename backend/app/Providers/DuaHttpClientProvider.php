<?php

namespace App\Providers;

use App\Services\Dua\DuaHttpClient;
use Illuminate\Support\ServiceProvider;

class DuaHttpClientProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DuaHttpClient::class, function () {
            $url = config('dua.url');

            return new DuaHttpClient(
                $url
            );
        });
    }

    public function boot(): void
    {
    }
}
