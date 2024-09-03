<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions;

use App\Domains\Accounts\Account;
use App\Domains\Accounts\Actions\AddBalance;
use App\Domains\Donors\Donor;
use App\Domains\Employees\Employee;
use App\Domains\RevenueStreams\RevenueStream;
use App\Domains\Tags\Actions\CreateTags;
use App\Domains\Transactions\Transaction;
use App\Domains\Vendors\Vendor;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
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
        $params['amount'] = $params['amount'] * 100;
        
        if ($params['fromable_type'] === Account::class)
            AddBalance::run(
            ['id' => $params['fromable_id'], 'amount' => -1 * $params['amount']]
        );

        if ($params['toable_type'] === Account::class)
            AddBalance::run(
            ['id' => $params['toable_id'], 'amount' => $params['amount']]
        );

        $tag_ids = $params['tag_ids'];
        unset($params['tag_ids']);
        $tag_ids = CreateTags::run($tag_ids);
        $transaction = Transaction::create($params);

        $transaction->tags()->sync($tag_ids);

        return $transaction;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'amount' => ['required', 'integer', 'min:0'],
            'author_id' => ['required', 'exists:users,id'],
            'fromable_type' => [
                'required',
                'in:' . implode(
                    ',', [Account::class, Donor::class, RevenueStream::class]
                    )
            ],
            'fromable_id' => ['required', 'uuid'],
            'toable_type' => [
                'required',
                'in:' . implode(
                    ',', [Account::class, Employee::class, Vendor::class]
                    )
            ],
            'toable_id' => ['required', 'uuid'],
            'note' => ['nullable', 'string'],
            'tag_ids' => ['nullable', 'array'],
        ];
    }

    public function afterValidator(
        Validator $validator,
        ActionRequest $request
    ): void {
        $fromable = $request->fromable_type;
        $toable = $request->toable_type;

        if ($fromable::find($request->fromable_id) === null)
            $validator->errors()->add('fromable_id', 'Invalid fromable selected');

        if ($toable::find($request->toable_id) === null)
            $validator->errors()->add('toable_id', 'Invalid toable selected');
    }

    public function asController(Request $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(
        Transaction $transaction,
        Request $request
    ): array {
        return [
            'message' => 'Transaction created successfully',
        ];
    }
}
