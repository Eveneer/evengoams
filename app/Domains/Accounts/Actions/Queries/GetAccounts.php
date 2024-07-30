<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Actions\Queries;

use Illuminate\Http\Request;
use App\Domains\Accounts\Account;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAccounts
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle()
    {
        return Account::paginate(10);
    }

    public function asController(Request $request)
    {
        return $this->handle($request->validated());
    }
}