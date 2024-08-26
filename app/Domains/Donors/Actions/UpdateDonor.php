<?php

declare(strict_types=1);

namespace App\Domains\Donors\Actions;

use App\Domains\Donors\Donor;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditDonor
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Donor $donor, array $params): Donor
    {
        $donor->update($params);
        return $donor;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:donors,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_individual' => ['required', 'boolean'],
            'details' => ['required', 'json'],
        ];
    }

    public function asController(Donor $donor, Request $request)
    {
        return $this->handle($donor, $request->validated());
    }

    public function jsonResponse(Donor $donor, Request $request): array
    {
        return [
            'message' => 'Donor updated successfully',
        ];
    }
}
