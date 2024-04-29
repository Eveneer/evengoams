<?php

declare(strict_types=1);

namespace App\Domains\Pledges\Actions;

use App\Domains\Pledges\Pledge;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditPledge
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Pledge $pledge, array $params): Pledge
    {
        $pledge->update($params);
        return $pledge;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function asController(Pledge $pledge, Request $request)
    {
        return $this->handle($pledge, $request->validated());
    }

    public function jsonResponse(Pledge $pledge, Request $request): array
    {
        return [
            'message' => 'Pledge updated successfully',
        ];
    }
}
