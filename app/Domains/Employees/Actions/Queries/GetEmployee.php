<?php

declare(strict_types=1);

namespace App\Domains\Employees\Actions\Queries;

use App\Domains\Employees\Employee;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetEmployee
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

    public function handle(string $id): Employee | null
    {
        return Employee::find($id);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:employees,id'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->get('id'));
    }

    public function jsonResponse(Employee $employee): array
    {
        return [
            'data' => $employee,
            'message' => 'Employee retrieved successfully',
        ];
    }
}
