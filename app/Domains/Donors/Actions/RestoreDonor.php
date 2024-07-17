<?php

declare(strict_types=1);

namespace App\Domains\Donors\Actions;

use App\Domains\Donors\Donor;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreDonor
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): Donor
    {
        $donor = Donor::withTrashed()->where('id', $params['id'])->first();
        $donor->restore();

        return $donor;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:donors,id'],
        ];
    }

    public function asController(Request $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Donor $donor, Request $request): array
    {
        return [
            'message' => 'Donor restored successfully',
        ];
    }
}
