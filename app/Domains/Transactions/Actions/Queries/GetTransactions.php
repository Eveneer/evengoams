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
        $tags = GetTags::run($per_page, $search_term);
        $accounts = GetAccounts::run($per_page, $search_term);
        $donors = GetDonors::run($per_page, $search_term);
        $vendors = GetVendors::run($per_page, $search_term);
        $employees = GetEmployees::run($per_page, $search_term);
        $revenue_streams = GetRevenueStreams::run($per_page, $search_term);
        
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
                ->orwhereHasMorph('fromable', [Donor::class], function ($query) use ($donors) {
                        $query->whereIn('id', $donors->pluck('id'));
                    })
                ->orwhereHasMorph('fromable', [RevenueStream::class], function ($query) use ($revenue_streams) {
                        $query->whereIn('id', $revenue_streams->pluck('id'));
                    })
                ->orwhereHasMorph('toable', [Account::class], function ($query) use ($accounts) {
                        $query->whereIn('id', $accounts->pluck('id'));
                    })
                ->orwhereHasMorph('toable', [Vendor::class], function ($query) use ($vendors) {
                    $query->whereIn('id', $vendors->pluck('id'));
                })
                ->orwhereHasMorph('toable', [Employee::class], function ($query) use ($employees) {
                    $query->whereIn('id', $employees->pluck('id'));
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
