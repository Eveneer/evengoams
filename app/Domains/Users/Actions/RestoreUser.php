<?php

declare(strict_types=1);

namespace App\Domains\Users\Actions;

use App\Domains\Users\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreUser
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): User
    {
        $user = User::withTrashed()->where('id', $params['id'])->first();
        $user->restore();

        return $user;
    }

    public function rules(): array
    {
        return [
            'id' => ['exists:']
        ];
    }

    public function asController(Request $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(User $user, Request $request): array
    {
        return [
            'message' => 'User restored successfully',
        ];
    }
}
