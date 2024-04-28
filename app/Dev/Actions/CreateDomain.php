<?php

declare(strict_types=1);

namespace App\Dev\Actions;

use App\Dev\Exceptions\InvalidParamsException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateDomain extends DevTool
{
    public function getCommandSignature(): string
    {
        return 'make:domain {domain}';
    }

    public function getCommandDescription(): string
    {
        return 'Create a new domain with generic folders and empty crud action files';
    }

    public function asCommand(Command $command): void
    {
        $this->handle(['domain' => $command->argument('domain')]);
    }

    public function runDevTool(mixed $params = null): void
    {
        if (!array_key_exists('domain', $params))
            throw new InvalidParamsException();

        $singularised_domain = Str::singular(ucfirst($params['domain']));
        CreateFolder::run(['path' => $params['domain'] . '/Actions']);
        CreateFolder::run(['path' => $params['domain'] . '/Enums']);

        $files = [
            ['type' => 'create', 'filename' => "Create$singularised_domain"],
            ['type' => 'update', 'filename' => "Update$singularised_domain"],
            ['type' => 'trash', 'filename' => "Trash$singularised_domain"],
            ['type' => 'restore', 'filename' => "Restore$singularised_domain"],
        ];
        
        foreach ($files as $file)
            CreateFile::run(['domain' => $params['domain'], ...$file]);
    }
}

