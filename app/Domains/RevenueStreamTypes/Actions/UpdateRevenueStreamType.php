<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreamTypes\Actions;

use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Domains\RevenueStreamTypes\RevenueStreamType;
use App\Domains\RevenueStreamTypes\Enums\RevenueStreamTypesEnum;

class EditRevenueStreamType
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(RevenueStreamType $revenue_stream_type, array $params): RevenueStreamType
    {
        $revenue_stream_type->update($params);
        return $revenue_stream_type;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:revenue_stream_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'properties' => ['required', 'array'],
            'properties.*.name' => ['required', 'string'],
            'properties.*.type' => ['required', 'in:' . implode(',', RevenueStreamTypesEnum::getValues())],
        ];
    }

    public function asController(RevenueStreamType $revenue_stream_type, Request $request)
    {
        return $this->handle($revenue_stream_type, $request->validated());
    }

    public function jsonResponse(RevenueStreamType $revenue_stream_type, Request $request): array
    {
        return [
            'message' => 'RevenueStreamType updated successfully',
        ];
    }
}
