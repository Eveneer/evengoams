<?php

declare(strict_types=1);

namespace App\Domains\Configs\Actions;

use App\Domains\Configs\Config;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateConfig
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Config $config, array $params): Config
    {
        $config->update($params);
        return $config;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function asController(Config $config, Request $request)
    {
        return $this->handle($config, $request->validated());
    }

    public function jsonResponse(Config $config, Request $request): array
    {
        return [
            'message' => 'Config updated successfully',
        ];
    }
}
