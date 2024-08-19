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

    public function handle(string $search_term): array
    {
        
        return Donor::where('name', 'like', '%' . $search_term . '%')
            ->orWhere('phone', 'like', '%' . $search_term . '%')
            ->orWhere('email', 'like', '%' . $search_term . '%')
            ->orWhere('address', 'like', '%' . $search_term . '%')
            ->orWhere('details', 'like', '%' . $search_term . '%')
            ->paginate(10);
        
    }

    public function rules(): array
    {
        return [
            'search_term' => ['required', 'string'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        $search_term = $request->input('search_term');
        return $this->handle($search_term);
    }

    public function jsonResponse(array $donors): array
    {
        return [
            'data' => $donors,
            'message' => count($donors) . ' donors found',
        ];
    }
}
