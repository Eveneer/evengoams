<?php

declare(strict_types=1);

namespace App\Dev\Exceptions;

use Exception;
use Throwable;

class InvalidFileTypeException extends Exception
{
    public function __construct(
        string $message = 'Invalid file type specified in file create dev tool.',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
