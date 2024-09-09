<?php

declare(strict_types=1);

namespace App\Domains\RevenueStreams\Actions;

use App\Domains\RevenueStreams\RevenueStream;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class TrashRevenueStream
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
        $revenuestream = RevenueStream::findOrFail($id);

        return $revenuestream->delete();
    }

    public function asController(string $id)
    {
        return $this->handle($id);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:revenue_streams,id'],
        ];
    }

    public function jsonResponse(bool $deleted): array
    {
        $success = $deleted ? 'successful' : 'unsuccessful';

        return [
            'message' => "RevenueStream delete $success",
        ];
    }
}
