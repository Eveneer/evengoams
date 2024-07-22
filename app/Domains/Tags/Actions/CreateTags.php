<?php

declare(strict_types=1);

namespace App\Domains\Tags\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class CreateTags
{
    use AsAction;

    public function handle(array $tags): array
    {
        $tag_ids = [];

        foreach ($tags as $tag)
            $tag_ids[] = CreateTag::run(['name' => $tag])->id;

        return $tag_ids;
    }
}
