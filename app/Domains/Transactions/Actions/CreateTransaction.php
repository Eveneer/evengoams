<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions;

use App\Domains\Transactions\Transaction;
use App\Domains\Transactions\Enums\TransactionTypesEnum;
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
            'date' => ['required', 'date'],
            'amount' => ['required', 'integer', 'min:0'],
            'author_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:' . implode(',', TransactionTypesEnum::asArray())],
            'fromable_type' => ['required', 'string', 'in:RevenueStream,Donor,Account'],
            'fromable_id' => ['required', 'uuid'],
            'toable_type' => ['required', 'string', 'in:Employee,Vendor,Account'],
            'toable_id' => ['required', 'uuid'],
            'parent_id' => ['nullable', 'uuid', 'exists:transactions,id'],
            'note' => ['nullable', 'string'],
            'tag_ids' => ['nullable', 'json'],
            'is_last' => ['boolean'],
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
