<?php

declare(strict_types=1);

namespace App\Domains\Employees\Actions\Queries;

use App\Domains\Employees\Employee;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetEmployees
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access) {
            return Response::allow();
        }

        return Response::deny('You are unauthorized to perform this action');
    }

    public function handle(
        ?int $per_page = 10, 
        ?string $search_term = ''
    ): Collection | LengthAwarePaginator {
        $query = Employee::query();

        if ($search_term) {
            $query
                ->where('first_name', 'like', "%$search_term%")
                ->orWhere('last_name', 'like', "%$search_term%")
                ->orWhere('email', 'like', "%$search_term%")
                ->orWhere('phone_number', 'like', "%$search_term%")
                ->orWhere('position', 'like', "%$search_term%");
        }
    
        return $per_page === null ?
            $query->get() :
            $query->paginate($per_page);
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
        return $this->handle(
            $request->per_page,
            $request->search_term,
        );
    }

    public function jsonResponse(
        Collection | LengthAwarePaginator $employees, 
        ActionRequest $request
    ): array {
        $message = count($employees) . ' employees ';
        $message .= $request->search_term ? 'found' : 'fetched';

        return [
            'data' => $employees,
            'message' => $message . ' successfully',
        ];
    }
}
