<?php

declare(strict_types=1);

namespace App\Domains\Vendors\Actions;

use App\Domains\Vendors\Vendor;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreVendor
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): bool
    {
        $vendor = Vendor::withTrashed()->where('id', $params['id'])->first();

        return $vendor->restore();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:vendors,id']
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(bool $restored): array
    {
        $success = $restored ? 'successful' : 'unsuccessful';

        return [
            'message' => "Vendor restore $success",
        ];
    }
}
