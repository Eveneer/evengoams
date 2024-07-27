<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreamTypes\Actions;

use App\Domains\RevenueStreamTypes\RevenueStreamType;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
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

    public function handle(RevenueStreamType $revenuestreamtype, array $params): RevenueStreamType
    {
        $revenuestreamtype->update($params);
        return $revenuestreamtype;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function asController(RevenueStreamType $revenuestreamtype, Request $request)
    {
        return $this->handle($revenuestreamtype, $request->validated());
    }

    public function jsonResponse(RevenueStreamType $revenuestreamtype, Request $request): array
    {
        return [
            'message' => 'RevenueStreamType updated successfully',
        ];
    }
}
