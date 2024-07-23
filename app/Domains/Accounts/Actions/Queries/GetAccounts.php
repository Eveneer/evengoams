<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Actions\Queries;

use App\Domains\Accounts\Account;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAccounts
{
    use AsAction;

    public function handle()
    {
        return Account::paginate(10);
    }
}