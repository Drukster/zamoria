<?php

namespace App\Utils;

/**
 * @template DataType
 */
class OperationResult
{
    /**
     * @param string|null $message
     * @param array|null $errors
     * @param bool $isError
     * @param DataType $data
     * @param int $code
     */
    public function __construct(
        public ?string $message = '',
        public ?array  $errors = [],
        public bool    $isError = false,
        public mixed   $data = null,
        public int     $code = 200
    )
    {
    }

    /**
     * @param DataType $data
     * @param int $code
     * @return self<DataType>
     */
    public static function success(
        mixed $data = null,
        int   $code = 200
    ): self
    {
        return new self(
            data: $data,
            code: $code
        );
    }

    public static function error(
        string $message = '',
        array  $errors = [],
        int    $code = 400
    ): self
    {
        return new self(
            message: $message,
            errors: $errors,
            isError: true,
            code: $code
        );
    }
}
