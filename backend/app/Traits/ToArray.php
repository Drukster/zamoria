<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ToArray
{
    public function toArray(): array
    {
        $result = [];
        foreach (get_object_vars($this) as $key => $value) {
            $result[Str::camel($key)] = $value;
        }

        return $result;
    }

    public function toArrayAsSnakeCase(): array
    {
        $result = [];
        foreach (get_object_vars($this) as $key => $value) {
            $result[Str::snake($key)] = $value;
        }

        return $result;
    }
}
