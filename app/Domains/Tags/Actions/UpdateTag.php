<?php

declare(strict_types=1);

namespace App\Domains\Tags\Actions;

use App\Domains\Tags\Tag;
use App\Domains\Tags\Enums\TagModelsEnum;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditTag
{
    use AsAction;

    public function authorize(ActionRequest $request): Response
    {
        $user = $request->user();
        
        if ($user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(Tag $tag, array $params): Tag
    {
        $tag->update($params);
        return $tag;
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $key = $request->name;
        
        // remove multiple spaces
        $key = preg_replace('/\s+/', ' ', $key);
        // convert spaces to dashes
        $key = str_replace(' ', '-', $key);
        // lowercase the key
        $key = strtolower($key);

        $request->merge(['key' => $key]);
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:tags,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', 'unique:tags,key,' . request()->route('tag')->id],
            'model' => ['required', 'in:' . implode(',', TagModelsEnum::getValues())],
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
