<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Actions;

use App\Domains\Accounts\Account;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAccounts
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access) {
            return Response::allow();
        }

        return Response::deny('You are unauthorized to perform this action');
    }

    public function handle(): array
    {
        return Account::paginate(10)->toArray();
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle();
    }

    public function jsonResponse(array $accounts): array
    {
        return [
            'data' => $accounts,
            'message' => count($accounts) . ' accounts fetched successfully',
        ];
    }
}
