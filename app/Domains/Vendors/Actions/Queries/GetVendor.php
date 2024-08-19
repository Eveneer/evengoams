<?php

declare(strict_types=1);

namespace App\Domains\Vendors\Actions;

use App\Domains\Vendors\Vendor;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetVendor
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

    public function handle(string $id): Vendor | null
    {
        return Vendor::find($id);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:donors,id'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->get('id'));
    }

    public function jsonResponse(Vendor $vendor): array
    {
        return [
            'data' => $vendor,
            'message' => 'Vendor retrieved successfully',
        ];
    }
}
