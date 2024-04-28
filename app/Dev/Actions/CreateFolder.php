<?php

declare(strict_types=1);

namespace App\Dev\Actions;

use App\Dev\Exceptions\InvalidParamsException;
use Illuminate\Console\Command;

class CreateFolder extends DevTool
{
    public function getCommandSignature(): string
    {
        return 'make:domain-folder {path}';
    }

    public function getCommandDescription(): string
    {
        return 'Create a new folder in the domain layer';
    }

    public function asCommand(Command $command): void
    {
        $this->handle([
            'path' => $command->argument('path')
        ]);
    }

    public function runDevTool(mixed $params = null): void
    {
        if (!array_key_exists('path', $params))
            throw new InvalidParamsException();
        
        $parts = explode('/', $params['path']);
        $path = "app/Domains";

        foreach ($parts as $part) {
            $path .= "/$part";
            
            if (! is_dir($path))
                mkdir($path, 0755, true);
        }
    }
}

