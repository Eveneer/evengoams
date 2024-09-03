<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreams\Actions;

use App\Domains\RevenueStreams\RevenueStream;
use App\Domains\RevenueStreamTypes\RevenueStreamType;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;


class EditRevenueStream
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(RevenueStream $revenue_stream, array $params): RevenueStream
    {
        $revenue_stream->update($params);
        return $revenue_stream;
    }

    public function rules(ActionRequest $request): array
    {
        $rules = [
            'id' => ['required', 'exists:revenue_streams,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'type_id' => ['sometimes', 'exists:revenue_stream_types,id'],
            'values' => ['sometimes', 'array'],
        ];

        if ($request->has('type_id')) {
            $type = RevenueStreamType::find($request->type_id);
            if ($type) {
                foreach ($type->properties as $property) {
                    $rules['values.' . $property['name']] = ['required', Rule::in(['single_line', 'multi_line', 'text', 'range', 'radio', 'checkbox', 'dropbox', 'repeater'])];
                }
            }
        }

        return $rules;
    }

    public function asController(RevenueStream $revenue_stream, Request $request)
    {
        return $this->handle($revenue_stream, $request->validated());
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
