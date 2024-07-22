<?php

namespace App\Domains\Tags;

use App\Domains\Tags\Enums\TagModelsEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'key',
        'model',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function transactions(): Builder
    {
        return DB::table('transaction')
            ->where('tag_ids', 'LIKE', "%$this->id%");
    }

    public function vendors(): Builder
    {
        return DB::table('vendor')
            ->where('tag_ids', 'LIKE', "%$this->id%");
    }

    public static function constructKey(string $tag): string
    {
        // remove multiple spaces
        $tag = preg_replace('/\s+/', ' ', $tag);
        // convert spaces to dashes
        $tag = str_replace(' ', '-', $tag);
        // lowercase the key
        $tag = strtolower($tag);

        return $tag;
    }

    public static function exists(string $tag, string $model): bool | Tag
    {
        $tag = self::where('key', self::constructKey($tag))->where('model', $model)->first();

        return $tag === null ? false : $tag;
    }
}
