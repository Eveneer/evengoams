<?php

declare(strict_types=1);

namespace App\Domains\Users\Actions;

use App\Domains\Users\User;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreUser
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): bool
    {
        $user = User::withTrashed()->where('id', $params['id'])->first();

        return $user->restore();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:users,id'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(bool $restored): array
    {
        $success = $restored ? 'successful' : 'unsuccessful';

        return [
            'message' => "User restore $success",
        ];
    }
}
