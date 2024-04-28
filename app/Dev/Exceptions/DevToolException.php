<?php

declare(strict_types=1);

namespace App\Dev\Exceptions;

use Exception;
use Throwable;

class DevToolException extends Exception
{
    public function __construct(
        string $message = 'Dev Tool Actions can only be used in local env.',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
