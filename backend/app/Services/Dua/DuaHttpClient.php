<?php

namespace App\Services\Dua;

use Illuminate\Support\Facades\Http;

readonly class DuaHttpClient
{
    public function __construct(
        private ?string $url,
    )
    {
    }

    public function get(): string
    {
        return Http::get($this->url)
            ->body();
    }
}
