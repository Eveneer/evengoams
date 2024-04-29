<?php

declare(strict_types=1);

namespace App\Domains\Vendors\Actions;

use App\Domains\Vendors\Vendor;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditVendor
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Vendor $vendor, array $params): Vendor
    {
        $vendor->update($params);
        return $vendor;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function asController(Vendor $vendor, Request $request)
    {
        return $this->handle($vendor, $request->validated());
    }

    public function jsonResponse(Vendor $vendor, Request $request): array
    {
        return [
            'message' => 'Vendor updated successfully',
        ];
    }
}
