<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreams\Actions;

use App\Domains\RevenueStreams\RevenueStream;
use App\Domains\RevenueStreamTypes\RevenueStreamType;
use App\Domains\RevenueStreamTypes\Enums\RevenueStreamTypesEnum;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateRevenueStream
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(string $id, array $params): RevenueStream
    {
        $revenue_stream = RevenueStream::findOrFail($id);
        $revenue_stream->update($params);

        return $revenue_stream;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:revenue_streams,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'type_id' => ['sometimes', 'exists:revenue_stream_types,id'],
            'values' => ['sometimes', 'array'],
        ];

    }

    public function asController(string $id, ActionRequest $request)
    {
        return $this->handle($id, $request->validated());
    }

    public function jsonResponse(
        RevenueStream $revenue_stream,
         Request $request
    ): array {
        return [
            'message' => 'RevenueStream updated successfully',
        ];
    }
}
