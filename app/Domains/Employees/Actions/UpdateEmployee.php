<?php

declare(strict_types=1);

namespace App\Domains\Employees\Actions;

use App\Domains\Employees\Employee;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditEmployee
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Employee $employee, array $params): Employee
    {
        $employee->update($params);
        return $employee;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:employees,id'],
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:employees,email,' . request()->id],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:20'],
            'position' => ['sometimes', 'string', 'max:255'],
            'salary' => ['sometimes', 'numeric', 'min:0'],
            'hire_date' => ['sometimes', 'date'],
        ];
    }

    public function asController(Employee $employee, Request $request)
    {
        return $this->handle($employee, $request->validated());
    }

    public function jsonResponse(Employee $employee, Request $request): array
    {
        return [
            'message' => 'Employee updated successfully',
        ];
    }
}
