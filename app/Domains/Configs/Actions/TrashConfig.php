<?php

declare(strict_types=1);

namespace App\Domains\Configs\Actions;

use App\Domains\Configs\Config;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class TrashConfig
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Config $config): bool
    {
        return $config->delete();
    }

    public function asController(Config $config)
    {
        return $this->handle($config);
    }

    public function jsonResponse(bool $deleted): array
    {
        $success = $deleted ? 'successful' : 'unsuccessful';

        return [
            'message' => "Config delete $success",
        ];
    }
}
