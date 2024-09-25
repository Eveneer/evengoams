<?php

declare(strict_types=1);

namespace App\Domains\Pledges\Actions;

use App\Domains\Pledges\Pledge;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class TrashPledge
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
        $pledge = Pledge::findOrFail($id);

        return $pledge->delete();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:pledges,id'],
        ];
    }

    public function asController(string $id)
    {
        return $this->handle($id);
    }

    public function jsonResponse(bool $deleted): array
    {
        $success = $deleted ? 'successful' : 'unsuccessful';

        return [
            'message' => "Pledge delete $success",
        ];
    }
}
