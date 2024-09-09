<?php

namespace App\Domains\Tags;

use App\Domains\Transactions\Transaction;
use App\Domains\Vendors\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'key',
        'model',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class);
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class);
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

    public static function exists(string $tag): bool | Tag
    {
        $tag = self::where('key', self::constructKey($tag))->first();

        return $tag === null ? false : $tag;
    }
}
