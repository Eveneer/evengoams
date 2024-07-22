<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions;

use App\Domains\Accounts\Account;
use App\Domains\Donors\Donor;
use App\Domains\Employees\Employee;
use App\Domains\RevenueStreams\RevenueStream;
use App\Domains\Transactions\Transaction;
use App\Domains\Transactions\Enums\TransactionTypesEnum;
use App\Domains\Vendors\Vendor;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
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
            'type' => ['sometimes', 'in:' . implode(',', TransactionTypesEnum::getValues())],
            'fromable_type' => [
                'required',
                'in:' . implode(',', [Account::class, Donor::class, RevenueStream::class])
            ],
            'fromable_id' => ['required', 'uuid'],
            'toable_type' => [
                'required',
                'in:' . implode(',', [Account::class, Employee::class, Vendor::class])
            ],
            'toable_id' => ['required', 'uuid'],
            'parent_id' => ['sometimes', 'nullable', 'exists:transactions,id'],
            'note' => ['sometimes', 'nullable', 'string'],
            'tag_ids' => ['sometimes', 'nullable', 'json'],
            'is_last' => ['sometimes', 'boolean'],
        ];
    }

    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        $fromable = $request->fromable_type;
        $toable = $request->toable_type;

        if ($fromable::find($request->fromable_id) === null)
            $validator->errors()->add('fromable_id', 'Invalid fromable selected');

        if ($toable::find($request->toable_id) === null)
            $validator->errors()->add('toable_id', 'Invalid toable selected');
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
