<?php

declare(strict_types=1);

namespace App\Domains\Pledges\Actions;

use App\Domains\Pledges\Pledge;
use App\Domains\Pledges\Enums\PledgeRecursEnum;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdatePledge
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(string $id, array $params): Pledge
    {
        $pledge = Pledge::findOrFail($id);
        $pledge->update($params);

        return $pledge;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:pledges,id'],
            'donor_id' => ['sometimes', 'exists:donors,id'],
            'amount' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'recurs' => ['sometimes', 'in:' . implode(',', PledgeRecursEnum::getValues())],
            'due_date' => ['sometimes', 'nullable', 'date'],
        ];
    }

    public function asController(string $id, ActionRequest $request)
    {
        return $this->handle($id, $request->validated());
    }

    public function jsonResponse(Pledge $pledge, Request $request): array
    {
        return [
            'message' => 'Pledge updated successfully',
        ];
    }
}
