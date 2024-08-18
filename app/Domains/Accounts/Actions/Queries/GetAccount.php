<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Actions;

use App\Domains\Accounts\Account;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAccount
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

    public function handle(string $id): Account | null
    {
        return Account::find($id);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:accounts,id'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->get('id'));
    }

    public function jsonResponse(Account $account): array
    {
        return [
            'data' => $account,
            'message' => 'Account retrieved successfully',
        ];
    }
}
