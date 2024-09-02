<?php

declare(strict_types=1);

namespace App\Domains\Tags\Actions\Queries;

use App\Domains\Tags\Tag;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTag
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access) {
            return Response::allow();
        }

        return Response::deny('You are unauthorized to perform this action');
    }

    public function handle(string $id): Tag | null
    {
        return Tag::find($id);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:tags,id'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request->get('id'));
    }

    public function jsonResponse(Tag $tag): array
    {
        return [
            'data' => $tag,
            'message' => 'Tag retrieved successfully',
        ];
    }
}
