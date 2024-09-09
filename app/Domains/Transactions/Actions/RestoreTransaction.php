<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions;

use App\Domains\Transactions\Transaction;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreTransaction
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): bool
    {
        $transaction = Transaction::withTrashed()->where('id', $params['id'])->first();

        return $transaction->restore();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:transactions,id'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(bool $restored): array
    {
        $success = $restored ? 'successful' : 'unsuccessful';

        return [
            'message' => "Transaction restore $success",
        ];
    }
}
