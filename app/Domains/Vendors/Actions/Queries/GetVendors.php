<?php

declare(strict_types=1);

namespace App\Domains\Vendors\Actions;

use App\Domains\Vendors\Vendor;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetVendors
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

    public function handle(?string $search_term, int $per_page): array
    {
        if ($search_term) {

            return Vendor::where('name', 'like', '%' . $search_term . '%')
            ->orWhere('contacts', 'like', '%' . $search_term . '%')
            ->paginate(10);
        }

        return Vendor::paginate($per_page)->toArray();
    }

    public function rules(): array
    {
        return [
            'search_term' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        $search_term = $request->input('search_term', null);
        $per_page = $request->input('per_page', 10);

        return $this->handle($search_term, $per_page);
    }

    public function jsonResponse(array $vendors, ActionRequest $request): array
    {
        $message = count($vendors) . ' vendors ';
        $message .= $request->input('search_term') ? 'found' : 'fetched';

        return [
            'data' => $vendors,
            'message' => $message . ' successfully',
        ];
    }
}
