<?php

declare(strict_types=1);

namespace App\Domains\Users\Actions;

use App\Domains\Users\Enums\UserTypesEnum;
use App\Domains\Users\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUser
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): User
    {
        return User::create($params);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'type' => ['required', 'in:' . implode(',', UserTypesEnum::getValues())],
        ];
    }

    public function asController(Request $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(User $user, Request $request): array
    {
        return [
            'message' => 'User created successfully',
        ];
    }
}
