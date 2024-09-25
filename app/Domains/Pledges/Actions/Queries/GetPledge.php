<?php

declare(strict_types=1);

namespace App\Domains\Pledges\Actions\Queries;

use App\Domains\Pledges\Pledge;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPledge
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

    public function handle(string $id): Pledge | null
    {
        return Pledge::find($id);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:pledges,id'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->get('id'));
    }

    public function jsonResponse(Pledge $pledge): array
    {
        return [
            'data' => $pledge,
            'message' => 'Pledge retrieved successfully',
        ];
    }
}
