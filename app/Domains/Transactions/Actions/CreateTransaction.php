<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions;

use App\Domains\Transactions\Transaction;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTransaction
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): Transaction
    {
        return Transaction::create($params);
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function asController(Request $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Transaction $transaction, Request $request): array
    {
        return [
            'message' => 'Transaction created successfully',
        ];
    }
}
