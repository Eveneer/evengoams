<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Actions;

use App\Domains\Accounts\Account;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class AddBalance
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): void
    {
        $account = Account::find($params['id']);
        $account->balance = $account->balance + $params['amount'];
        $account->save();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'int']
        ];
    }

    public function asController(Request $request)
    {
        return $this->handle($request->validated());
    }
}