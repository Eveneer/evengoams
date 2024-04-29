<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions;

use App\Domains\Transactions\Transaction;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditTransaction
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Transaction $transaction, array $params): Transaction
    {
        $transaction->update($params);
        return $transaction;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function asController(Transaction $transaction, Request $request)
    {
        return $this->handle($transaction, $request->validated());
    }

    public function jsonResponse(Transaction $transaction, Request $request): array
    {
        return [
            'message' => 'Transaction updated successfully',
        ];
    }
}
