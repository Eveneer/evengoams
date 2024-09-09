<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Actions;

use App\Domains\Accounts\Account;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreAccount
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): bool
    {
        $account = Account::withTrashed()->where('id', $params['id'])->first();

        return $account->restore();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:accounts,id']
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(bool $restored): array
    {
        $success = $restored ? 'successful' : 'unsuccessful';

        return [
            'message' => "Account restoration was $success",
        ];
    }
}
