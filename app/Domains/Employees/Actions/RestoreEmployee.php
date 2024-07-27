<?php

declare(strict_types=1);

namespace App\Domains\Employees\Actions;

use App\Domains\Employees\Employee;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreEmployee
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): Employee
    {
        $employee = Employee::withTrashed()->where('id', $params['id'])->first();
        $employee->restore();

        return $employee;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:employees,id'],
        ];
    }

    public function asController(Request $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Employee $employee, Request $request): array
    {
        return [
            'message' => 'Employee restored successfully',
        ];
    }
}
