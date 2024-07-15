<?php

namespace App\Domains\Transactions;

use App\Domains\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'date',
        'amount',
        'author_id',
        'type',
        'fromable_type',
        'fromable_id',
        'toable_type',
        'toable_id',
        'parent_id',
        'note',
        'tag_ids',
        'is_last',
    ];

    protected $casts = [
        'tag_ids' => 'array',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function from(): MorphTo
    {
        return $this->morphTo();
    }

    public function to(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function child(): HasOne
    {
        return $this->hasOne(self::class, 'parent_id');
    }
}
