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

    public function handle(int $per_page): array
    {
        return Account::paginate($per_page)->toArray();
    }

    public function asController(ActionRequest $request)
    {
        $per_page = $request->input('per_page', 10);
        return $this->handle($per_page);
    }

    public function jsonResponse(array $accounts): array
    {
        return [
            'data' => $accounts,
            'message' => count($accounts) . ' accounts fetched successfully',
        ];
    }
}
