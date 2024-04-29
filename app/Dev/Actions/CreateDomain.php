<?php

declare(strict_types=1);

namespace App\Dev\Actions;

use App\Dev\Enums\DomainFileTypesEnum;
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
            ['type' => DomainFileTypesEnum::CREATE_ACTION, 'filename' => "Create$singularised_domain.php"],
            ['type' => DomainFileTypesEnum::UPDATE_ACTION, 'filename' => "Update$singularised_domain.php"],
            ['type' => DomainFileTypesEnum::TRASH_ACTION, 'filename' => "Trash$singularised_domain.php"],
            ['type' => DomainFileTypesEnum::RESTORE_ACTION, 'filename' => "Restore$singularised_domain.php"],
            ['type' => DomainFileTypesEnum::MODEL, 'filename' => "$singularised_domain.php"],
        ];
        
        foreach ($files as $file)
            CreateFile::run(['domain' => $params['domain'], ...$file]);
    }
}

