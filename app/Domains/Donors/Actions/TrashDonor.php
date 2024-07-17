<?php

declare(strict_types=1);

namespace App\Domains\Donors\Actions;

use App\Domains\Donors\Donor;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class TrashDonor
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Donor $donor): bool
    {
        return $donor->delete();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:donors,id'],
        ];
    }

    public function asController(Donor $donor)
    {
        return $this->handle($donor);
    }

    public function jsonResponse(bool $deleted): array
    {
        $success = $deleted ? 'successful' : 'unsuccessful';

        return [
            'message' => "Donor delete $success",
        ];
    }
}
