<?php

declare(strict_types=1);

namespace App\Dev\Actions;

use App\Domains\Users\Enums\UserTypesEnum;
use App\Domains\Users\User;
use Illuminate\Console\Command;

class CreateUserToken extends DevTool
{
    function getCommandSignature(): string
    {
        return 'make:token {user_type=admin}';
    }

    function getCommandDescription(): string
    {
        return 'Create user token for a given type of user, if user type is not provided, the default it admin.';
    }

    public function runDevTool(mixed $params = null): void
    {
        $user_type = $params['user_type'];
        $command = $params['command'];

        try {
            UserTypesEnum::fromValue($user_type);
        } catch (\Throwable $_) {
            $command->error('Invalid user type provided');
            return;
        }


        $user = User::whereType($user_type)->first();
        $user->tokens()->delete();
        $token = $user->createToken('dev');

        $command->info('New Token Created');
        $command->line("User Name: $user->name");
        $command->line("User Email: $user->email");
        $command->info("$token->plainTextToken");
    }

    public function asCommand(Command $command): void
    {
        $this->handle([
            'command' => $command,
            'user_type' => $command->argument('user_type')
        ]);
    }
}