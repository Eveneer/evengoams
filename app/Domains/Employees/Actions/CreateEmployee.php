<?php

declare(strict_types=1);

namespace App\Domains\Employees\Actions;

use App\Domains\Employees\Employee;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateEmployee
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): Employee
    {
        return Employee::create($params);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:employees,email'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'position' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'numeric', 'min:0'],
            'hire_date' => ['required', 'date'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Employee $employee, Request $request): array
    {
        return [
            'message' => 'Employee created successfully',
        ];
    }
}
