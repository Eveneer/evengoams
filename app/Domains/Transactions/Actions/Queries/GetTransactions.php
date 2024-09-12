<?php

declare(strict_types=1);

namespace App\Domains\Transactions\Actions\Queries;

use App\Domains\Accounts\Actions\Queries\GetAccounts;
use App\Domains\Donors\Actions\Queries\GetDonors;
use App\Domains\Tags\Actions\Queries\GetTags;
use App\Domains\Tags\Tag;
use App\Domains\Transactions\Transaction;
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

    public function handle(?int $per_page = 10, ?string $search_term = ''): Collection | LengthAwarePaginator
    {
        $transactions = Transaction::query();
        $tags = (new GetTags())->handle($per_page, $search_term);

        // $donors = (new GetDonors())->handle($per_page, $search_term);

        // $vendors = (new GetVendors())->handle($search_term);

        // $accounts = (new GetAccounts())->handle($per_page, $search_term);
        
        if ($search_term) {
            $transactions->where('note', 'like', "%$search_term%")
                ->orWhereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('id', $tags->pluck('id'));
                });
                // ->orWhereHas('donor', function ($query) use ($donors) {
                //     $query->whereIn('id', $donors->pluck('id'));
                // })
                // ->orWhereHas('vendor', function ($query) use ($vendors) {
                //     $query->whereIn('id', $vendors->pluck('id'));
                // })
                // ->orWhereHas('account', function ($query) use ($accounts) {
                //     $query->whereIn('id', $accounts->pluck('id'));
                // });
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
            'message' => $message . ' successfully',
        ];
    }
}
