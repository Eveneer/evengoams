<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreams\Actions;

use App\Domains\RevenueStreams\RevenueStream;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class TrashRevenueStream
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(RevenueStream $revenuestream): bool
    {
        return $revenuestream->delete();
    }

    public function asController(RevenueStream $revenuestream)
    {
        return $this->handle($revenuestream);
    }

    public function jsonResponse(bool $deleted): array
    {
        $success = $deleted ? 'successful' : 'unsuccessful';

        return [
            'message' => "RevenueStream delete $success",
        ];
    }
}
