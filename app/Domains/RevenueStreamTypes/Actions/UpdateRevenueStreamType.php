<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreamTypes\Actions;

use App\Domains\RevenueStreamTypes\Enums\RevenueStreamTypesEnum;
use App\Domains\RevenueStreamTypes\RevenueStreamType;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

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

        ];
    }

    public function withValidator(Validator $validator, ActionRequest $request): void
    {
        $validator->after(function (Validator $validator) use ($request) {
            $properties = $request->input('properties', []);
    
            $validateProperties = function (array $properties) use (&$validateProperties, $validator) {
                foreach ($properties as $index => $property) {
    
                    if (empty($property['name'])) {
                        $validator->errors()->add("name",'The name field is required.');
                    }
    
                    if (!in_array($property['type'], RevenueStreamTypesEnum::getValues())) {
                        $validator->errors()->add("type", 'The selected type is invalid.');
                    }
    
                    if ($property['type'] === 'repeater' && isset($property['properties'])) {
                        $validateProperties($property['properties']);
                    }
                }
            };
    
            $validateProperties($properties);
        });
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
