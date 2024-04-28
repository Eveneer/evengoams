<?php

declare(strict_types=1);

namespace App\Dev\Exceptions;

use Exception;
use Throwable;

class InvalidParamsException extends Exception
{
    public function __construct(
        string $message = 'Invalid params passed.',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
