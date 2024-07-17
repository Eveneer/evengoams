<?php

namespace App\Domains\Vendors;

use App\Domains\Tags\Enums\TagModelsEnum;
use App\Domains\Tags\Tag;
use App\Domains\Transactions\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class Vendor extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'type',
        'tag_ids',
        'contacts',
    ];

    protected $casts = [
        'tag_ids' => 'array',
        'contacts' => 'array',
    ];

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'toable');
    }

    public function tags(): Builder
    {
        return DB::table('tags')
            ->where('model', TagModelsEnum::VENDOR)
            ->whereIn('id', $this->tag_ids);
    }
}
