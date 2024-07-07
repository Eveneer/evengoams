<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Actions;

use App\Domains\Accounts\Account;
use App\Domains\Accounts\Enums\AccountTypesEnum;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditAccount
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Account $account, array $params): Account
    {
        $account->update($params);
        return $account;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid'],
            'name' => ['sometimes', 'string', 'max:255'],
            'balance' => ['sometimes', 'integer', 'min:0'],
            'type' => ['sometimes', 'in:' . implode(',', AccountTypesEnum::asArray())],
            'details' => ['sometimes', 'json'],
        ];
    }

    public function asController(Account $account, Request $request)
    {
        return $this->handle($account, $request->validated());
    }

    public function jsonResponse(Account $account, Request $request): array
    {
        return [
            'message' => 'Account updated successfully',
        ];
    }
}
