<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions;

use App\Domains\Transactions\Transaction;
use App\Domains\Transactions\Enums\TransactionTypesEnum;
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
            'id' => ['required', 'exists:transactions,id'],
            'date' => ['sometimes', 'date'],
            'amount' => ['sometimes', 'integer', 'min:0'],
            'author_id' => ['sometimes', 'exists:users,id'],
            'type' => ['sometimes', 'in:' . implode(',', TransactionTypesEnum::asArray())],
            'fromable_type' => ['sometimes', 'string', 'in:RevenueStream,Donor,Account'],
            'fromable_id' => ['sometimes', 'uuid'],
            'toable_type' => ['sometimes', 'string', 'in:Employee,Vendor,Account'],
            'toable_id' => ['sometimes', 'uuid'],
            'parent_id' => ['sometimes', 'nullable', 'uuid', 'exists:transactions,id'],
            'note' => ['sometimes', 'nullable', 'string'],
            'tag_ids' => ['sometimes', 'nullable', 'json'],
            'is_last' => ['sometimes', 'boolean'],
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
