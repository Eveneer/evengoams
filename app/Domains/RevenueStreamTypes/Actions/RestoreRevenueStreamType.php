<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreamTypes\Actions;

use App\Domains\RevenueStreamTypes\RevenueStreamType;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreRevenueStreamType
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
        $revenue_stream_type = RevenueStreamType::withTrashed()->where('id', $params['id'])->first();

        return $revenue_stream_type->restore();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:revenue_stream_types,id'],
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
            'message' => "RevenueStreamType restore $success",
        ];
    }
}
