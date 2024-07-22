<?php

declare(strict_types=1);

namespace App\Domains\Vendors\Actions;

use App\Domains\Tags\Actions\CreateTags;
use App\Domains\Tags\Enums\TagModelsEnum;
use App\Domains\Vendors\Vendor;
use App\Domains\Vendors\Enums\VendorTypesEnum;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateVendor
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): Vendor
    {
        $params['tag_ids'] = CreateTags::run($params['tag_ids'], TagModelsEnum::VENDOR);

        return Vendor::create($params);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:' . implode(',', VendorTypesEnum::asArray())],
            'tag_ids' => ['required', 'array'],
            'contacts' => ['nullable', 'array'],
            'contacts.*.name' => ['nullable', 'string'],
            'contacts.*.phone' => ['nullable', 'string'],
            'contacts.*.email' => ['nullable', 'email'],
        ];
    }

    public function asController(Request $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Vendor $vendor, Request $request): array
    {
        return [
            'message' => 'Vendor created successfully',
        ];
    }
}
