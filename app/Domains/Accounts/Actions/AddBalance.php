<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Actions;

use App\Domains\Accounts\Account;
use Lorisleiva\Actions\Concerns\AsAction;

class AddBalance
{
    use AsAction;

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
}