<?php

declare(strict_types=1);

namespace App\Domains\Donors\Actions;

use App\Domains\Donors\Donor;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class SearchDonor
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

    public function handle(string $searchTerm): array
    {
        $query = Donor::query();

        $query->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhere('phone', 'like', '%' . $searchTerm . '%')
              ->orWhere('email', 'like', '%' . $searchTerm . '%')
              ->orWhere('address', 'like', '%' . $searchTerm . '%')
              ->orWhere('details', 'like', '%' . $searchTerm . '%');

        return $query->get()->toArray();
    }

    public function rules(): array
    {
        return [
            'searchTerm' => ['required', 'string'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        $searchTerm = $request->input('searchTerm');
        return $this->handle($searchTerm);
    }

    public function jsonResponse(array $donors): array
    {
        return [
            'data' => $donors,
            'message' => count($donors) . ' donors found',
        ];
    }
}
