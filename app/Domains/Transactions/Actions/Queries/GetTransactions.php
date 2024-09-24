<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions\Queries;

use App\Domains\Accounts\Account;
use App\Domains\Accounts\Actions\Queries\GetAccounts;
use App\Domains\Donors\Donor;
use App\Domains\Donors\Actions\Queries\GetDonors;
use App\Domains\Employees\Employee;
use App\Domains\Employees\Actions\Queries\GetEmployees;
use App\Domains\RevenueStreams\RevenueStream;
use App\Domains\RevenueStreams\Actions\Queries\GetRevenueStreams;
use App\Domains\Tags\Actions\Queries\GetTags;
use App\Domains\Transactions\Transaction;
use App\Domains\Users\Actions\Queries\GetUsers;
use App\Domains\Vendors\Vendor;
use App\Domains\Vendors\Actions\Queries\GetVendors;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

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

    public function handle(
        ?int $per_page = 10, 
        ?string $search_term = ''
    ): Collection | LengthAwarePaginator {

        $transactions = Transaction::query();
        $tags = GetTags::run(null, $search_term);
        $users = GetUsers::run(null, $search_term);
        $accounts = GetAccounts::run(null, $search_term);
        
        if ($search_term) {
            $transactions->where('note', 'like', "%$search_term%")
                ->orWhereIn('author_id', $users->pluck('id'))
                ->orWhereIn('id', function ($query) use ($tags) {
                    $query->select('transaction_id')
                        ->from('tag_transaction')
                        ->whereIn('tag_id', $tags->pluck('id'));
                })
                ->orwhereHasMorph(
                    'fromable',
                    [Account::class, Donor::class, RevenueStream::class],
                    function ($query) use ($accounts, $search_term) {
                        $query->whereIn('id', [
                            ...$accounts->pluck('id'), 
                            ...GetDonors::run(null, $search_term)->pluck('id'), 
                            ...GetRevenueStreams::run(null, $search_term)->pluck('id')
                        ]);
                    }
                )
                ->orwhereHasMorph(
                    'toable',
                    [Account::class, Vendor::class, Employee::class],
                    function ($query) use ($accounts, $search_term) {
                        $query->whereIn('id', [
                            ...$accounts->pluck('id'), 
                            ...GetVendors::run(null, $search_term)->pluck('id'), 
                            ...GetEmployees::run(null, $search_term)->pluck('id')
                        ]);
                    }
                );
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

    public function jsonResponse(
        Collection | LengthAwarePaginator $transactions, 
        ActionRequest $request
    ): array {
        
        $message = count($transactions) . ' transactions ';
        $message .= $request->search_term ? 'found' : 'fetched';

        return [
            'data' => $transactions,
            'message' => $message . ' successfully',
        ];
    }
}
