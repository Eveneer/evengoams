<?php

declare(strict_types=1);

namespace App\Domains\Tags\Actions;

use App\Domains\Tags\Enums\TagModelsEnum;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTags
{
    use AsAction;

    public function handle(array $tags, string $model): array
    {
        $tag_ids = [];

        foreach ($tags as $tag)
            $tag_ids[] = CreateTag::run(['name' => $tag, 'model' => $model])->id;

        return $tag_ids;
    }
}
