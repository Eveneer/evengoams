<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions\Queries;

use App\Domains\Tags\Tag;
use App\Domains\Accounts\Account;
use Illuminate\Support\Collection;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use App\Domains\Transactions\Transaction;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Domains\Tags\Actions\Queries\GetTags;
use App\Domains\Donors\Actions\Queries\GetDonors;
use App\Domains\Vendors\Actions\Queries\GetVendors;
use App\Domains\Accounts\Actions\Queries\GetAccounts;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetTransactions
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

    public function handle(?int $per_page = 10, ?string $search_term = ''): Collection | LengthAwarePaginator
    {
        $transactions = Transaction::query();
        $tags = GetTags::run($per_page, $search_term);
        $accounts = GetAccounts::run($per_page, $search_term);
        
        if ($search_term) {
            $transactions->where('note', 'like', "%$search_term%")
                ->orWhereIn('id', function ($query) use ($tags) {
                    $query->select('transaction_id')
                        ->from('tag_transaction')
                        ->whereIn('tag_id', $tags->pluck('id'));
                    })
                ->orwhereHasMorph('fromable', [Account::class], function ($query) use ($accounts) {
                        $query->whereIn('id', $accounts->pluck('id'));
                    })
                ->orwhereHasMorph('toable', [Account::class], function ($query) use ($accounts) {
                        $query->whereIn('id', $accounts->pluck('id'));
                    });
        }
    
        return $per_page === null ?
            $transactions->get() :
            $transactions->paginate($per_page);
    }

    public function rules(): array
    {
        return [
            'search_term' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->per_page, $request->search_term);
    }

    public function jsonResponse(Collection | LengthAwarePaginator $transactions, ActionRequest $request): array
    {
        $message = count($transactions) . ' transactions ';
        $message .= $request->search_term ? 'found' : 'fetched';

        return [
            'data' => $transactions,
            'message' => $message . ' successfully',
        ];
    }
}
