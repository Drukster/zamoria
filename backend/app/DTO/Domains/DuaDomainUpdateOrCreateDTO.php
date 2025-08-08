<?php

namespace App\DTO\Domains;

use App\Traits\ToArray;
use Spatie\LaravelData\Data;

class DuaDomainUpdateOrCreateDTO extends Data
{
    use ToArray;

    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $content = null,
        public readonly ?string $translation = null,
        public readonly ?string $transliteration = null,
        public readonly ?string $arabic_text = null,
    )
    {
    }
}
