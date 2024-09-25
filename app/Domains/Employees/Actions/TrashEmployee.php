<?php

declare(strict_types=1);

namespace App\Domains\Employees\Actions;

use App\Domains\Employees\Employee;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class TrashEmployee
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(string $id): bool
    {
        $employee = Employee::findOrFail($id);

        return $employee->delete();
    }

    public function asController(string $id)
    {
        return $this->handle($id);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:employees,id'],
        ];
    }

    public function jsonResponse(bool $deleted): array
    {
        $success = $deleted ? 'successful' : 'unsuccessful';

        return [
            'message' => "Employee delete $success",
        ];
    }
}
