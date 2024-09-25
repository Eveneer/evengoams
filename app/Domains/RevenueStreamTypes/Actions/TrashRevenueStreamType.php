<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreamTypes\Actions;

use App\Domains\RevenueStreamTypes\RevenueStreamType;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class TrashRevenueStreamType
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(string $id): bool
    {
        $revenue_stream_type = RevenueStreamType::findOrFail($id);
        return $revenue_stream_type->delete();
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:revenue_stream_types,id'],
        ];
    }

    public function asController(string $id)
    {
        return $this->handle($id);
    }

    public function jsonResponse(bool $deleted): array
    {
        $success = $deleted ? 'successful' : 'unsuccessful';

        return [
            'message' => "RevenueStreamType delete $success",
        ];
    }
}
