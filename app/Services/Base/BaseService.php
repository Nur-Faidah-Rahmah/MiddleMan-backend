<?php

namespace App\Services\Base;

use App\Exceptions\ApiException;

abstract class BaseService
{
    protected function fail(
        string $message,
        int $status = 400
    ): never {

        throw new ApiException(
            $message,
            $status
        );

    }
}