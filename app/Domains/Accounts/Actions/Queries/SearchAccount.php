<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Actions;

use App\Domains\Accounts\Account;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class SearchAccount
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access) {
            return Response::allow();
        }

        return Response::deny('You are unauthorized to perform this action');
    }

    public function handle(string $search_term): array
    {
        $query = Account::query();

        $query->where('name', 'like', '%' . $search_term . '%')
              ->orWhere('details', 'like', '%' . $search_term . '%');

        return $query->get()->toArray();
    }

    public function rules(): array
    {
        return [
            'search_term' => ['required', 'string'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        $search_term = $request->input('searchTerm');
        return $this->handle($search_term);
    }

    public function jsonResponse(array $accounts): array
    {
        return [
            'data' => $accounts,
            'message' => count($accounts) . ' accounts found',
        ];
    }
}
