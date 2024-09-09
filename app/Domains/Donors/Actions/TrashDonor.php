<?php

declare(strict_types=1);

namespace App\Domains\Donors\Actions;

use App\Domains\Donors\Donor;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class TrashDonor
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
        $donor = Donor::findOrFail($id);

        return $donor->delete();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:donors,id'],
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
            'message' => "Donor delete $success",
        ];
    }
}
