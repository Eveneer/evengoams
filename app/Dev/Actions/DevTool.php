<?php

declare(strict_types=1);

namespace App\Dev\Actions;

use Closure;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsObject;

abstract class DevTool
{
    use AsCommand, AsObject;

    protected string $domain;
    protected string $namespace;

    abstract function getCommandSignature(): string;
    abstract function getCommandDescription(): string;
    abstract public function runDevTool(mixed $params = null): void;
    abstract public function asCommand(Command $command): void;

    public function getCommandHelp(): string
    {
        return "A help description has not been added for this command";
    }

    protected final function handle(mixed $params = null): void
    {
        if (env('APP_ENV') !== 'local') {
            throw new \Exception('This tool is only available in the local environment');
        }

        $this->runDevTool($params);
    }

    
}

