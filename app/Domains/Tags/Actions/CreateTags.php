<?php

declare(strict_types=1);

namespace App\Domains\Tags\Actions;

use App\Domains\Tags\Tag;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTags
{
    use AsAction;

    public function handle(array $tags): array
    {
        $tag_ids = [];

        foreach ($tags as $tag) {
            if (Str::isUuid($tag)) {
                $tag = Tag::find($tag);

                if ($tag !== null)
                    $tag_ids[] = $tag->id;

            } else {
                $tag_ids[] = CreateTag::run(['name' => $tag])->id;
            }
        }

        return $tag_ids;
    }
}
