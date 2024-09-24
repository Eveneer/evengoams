<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions;

use App\Domains\Accounts\Account;
use App\Domains\Accounts\Actions\AddBalance;
use App\Domains\Transactions\Transaction;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreTransaction
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
        $transaction = Transaction::withTrashed()->where('id', $params['id'])->first();

        if ($transaction->fromable_type === Account::class)
            AddBalance::run(['id' => $transaction->fromable_id, 'amount' => -1 * $transaction->amount]);

        if ($transaction->toable_type === Account::class)
            AddBalance::run(['id' => $transaction->toable_id, 'amount' => $transaction->amount]);

        return $transaction->restore();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:transactions,id'],
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
            'message' => "Transaction restore $success",
        ];
    }
}
