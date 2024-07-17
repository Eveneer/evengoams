<?php

namespace App\Domains\Tags;

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
}
