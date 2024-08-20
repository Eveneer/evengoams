<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions;

use App\Domains\Accounts\Account;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Response;
use App\Domains\Transactions\Transaction;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Domains\Accounts\Actions\AddBalance;

class TrashTransaction
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Transaction $transaction): bool
    {
        if ($transaction->fromable_type === Account::class) {
            AddBalance::run([
                'id' => $transaction->fromable_id, 
                'amount' => $transaction->amount
            ]);
        }

        if ($transaction->toable_type === Account::class) {
            AddBalance::run([
                'id' => $transaction->toable_id, 
                'amount' => -1 * $transaction->amount
            ]);
        }
        
        $transaction->tags()->detach();
        
        
        return $transaction->delete();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:transactions,id'],
        ];
    }


    public function asController(Transaction $transaction)
    {
        return $this->handle($transaction);
    }

    public function jsonResponse(bool $deleted): array
    {
        $success = $deleted ? 'successful' : 'unsuccessful';

        return [
            'message' => "Transaction delete $success",
        ];
    }
}
