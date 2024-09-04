<?php

declare(strict_types=1);

namespace App\Domains\Tags\Actions;

use App\Domains\Tags\Tag;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTag
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user && $user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Tag $tag, array $params): Tag
    {

    $existingTag = Tag::where('name', $params['name'])->first();

    if ($existingTag) {
        return $tag;
    }

    $params['key'] = Tag::constructKey($params['name']);
    $tag->update($params);
    return $tag;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:tags,id'],
            'name' => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function asController(Tag $tag, Request $request)
    {
        return $this->handle($tag, $request->validated());
    }

    public function jsonResponse(Tag $tag, Request $request): array
    {
        return [
            'message' => 'Tag updated successfully',
        ];
    }
}
