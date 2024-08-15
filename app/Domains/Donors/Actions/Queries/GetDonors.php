<?php

declare(strict_types=1);

namespace App\Domains\Donors\Actions;

use App\Domains\Donors\Donor;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetDonors
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access) {
            return Response::allow();
        }

        return Response::deny('You are unauthorized to perform this action');
    }

    public function handle(): array
    {
        return Donor::paginate(10)->toArray();
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle();
    }

    public function jsonResponse(array $donors): array
    {
        return [
            'data' => $donors,
            'message' => count($donors) . ' donors fetched successfully',
        ];
    }
}
