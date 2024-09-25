<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreamTypes\Actions\Queries;

use App\Domains\RevenueStreamTypes\RevenueStreamType;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetRevenueStreamType
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access) {
            return Response::allow();
        }

        return Response::deny('You are unauthorized to perform this action');
    }

    public function handle(string $id): RevenueStreamType | null
    {
        return RevenueStreamType::find($id);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:revenue_stream_types,id'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->get('id'));
    }

    public function jsonResponse(RevenueStreamType $revenuestreamtype): array
    {
        return [
            'data' => $revenuestreamtype,
            'message' => 'RevenueStreamType retrieved successfully',
        ];
    }
}
