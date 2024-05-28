<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreams\Actions;

use App\Domains\RevenueStreams\RevenueStream;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
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

    public function handle(RevenueStream $revenuestream, array $params): RevenueStream
    {
        $revenuestream->update($params);
        return $revenuestream;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function asController(RevenueStream $revenuestream, Request $request)
    {
        return $this->handle($revenuestream, $request->validated());
    }

    public function jsonResponse(RevenueStream $revenuestream, Request $request): array
    {
        return [
            'message' => 'RevenueStream updated successfully',
        ];
    }
}
