<?php

declare(strict_types=1);

namespace App\Domains\Users\Actions;

use App\Domains\Users\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUser
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(User $user, array $params): User
    {
        $user->update($params);
        return $user;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function asController(User $user, Request $request)
    {
        return $this->handle($user, $request->validated());
    }

    public function jsonResponse(User $user, Request $request): array
    {
        return [
            'message' => 'User updated successfully',
        ];
    }
}
