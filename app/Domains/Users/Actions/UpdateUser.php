<?php

declare(strict_types=1);

namespace App\Domains\Users\Actions;

use App\Domains\Users\Enums\UserTypesEnum;
use App\Domains\Users\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUser
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(string $id, array $params): User
    {
        $user = User::findOrFail($id);
        $user->update($params);

        return $user;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:users,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255'],
            'password' => ['sometimes', 'string', 'min:8'],
            'type' => ['sometimes', 'in:' . implode(',', UserTypesEnum::getValues())],
        ];
    }

    public function asController(string $id, ActionRequest $request)
    {
        return $this->handle($id, $request->validated());
    }

    public function jsonResponse(User $user, Request $request): array
    {
        return [
            'message' => 'User updated successfully',
        ];
    }
}
