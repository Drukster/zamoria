<?php

namespace App\DTO\Queries;

use App\Traits\ToArray;
use Spatie\LaravelData\Data;

class CategoryQueryBySlugDTO extends Data
{
    use ToArray;

    public function __construct(
        public readonly ?string $slug = null,
    )
    {
    }
}
