<?php

declare(strict_types=1);

namespace App\Domains\Users\Actions\Queries;

use App\Domains\Users\User;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUser
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

    public function handle(string $id): User | null
    {
        return User::find($id);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:users,id'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->get('id'));
    }

    public function jsonResponse(User $user): array
    {
        return [
            'data' => $user,
            'message' => 'User retrieved successfully',
        ];
    }
}
