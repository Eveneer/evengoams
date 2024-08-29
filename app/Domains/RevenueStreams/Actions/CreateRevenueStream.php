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

class CreateRevenueStream
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array $params): RevenueStream
    {
        return RevenueStream::create($params);
    }

    public function rules(ActionRequest $request): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type_id' => ['required', 'exists:revenue_stream_types,id'],
            'values' => ['required', 'array'],
        ];

        $type = RevenueStreamType::find($request->type_id);
        if ($type) {
            foreach ($type->properties as $property) {
                $rules['values.' . $property['name']] = ['required', Rule::in(['single_line', 'multi_line', 'text', 'range', 'radio', 'checkbox', 'dropbox', 'repeater'])];
            }
        }

        return $rules;
    }

    public function asController(Request $request)
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(RevenueStream $revenue_stream, Request $request): array
    {
        return [
            'message' => 'RevenueStream created successfully',
        ];
    }
}
