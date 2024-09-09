<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Actions;

use App\Domains\Accounts\Account;
use App\Domains\Accounts\Enums\AccountTypesEnum;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAccount
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(string $id, array $params): Account
    {
        $account = Account::findOrFail($id);
        $account->name = $params['name'] ?? $account->name;
        $account->type = $params['type'] ?? $account->type;
        $account->details = $params['details'] ?? $account->details;
        $account->save();

        return $account;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:accounts,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'balance' => ['sometimes', 'integer', 'min:0'],
            'type' => ['sometimes', 'in:' . implode(',', AccountTypesEnum::getValues())],
            'details' => ['sometimes', 'json'],
        ];
    }

    public function asController(string $id, ActionRequest $request)
    {
        return $this->handle($id, $request->validated());
    }

    public function jsonResponse(Account $account, Request $request): array
    {
        return [
            'message' => 'Account updated successfully',
        ];
    }
}
