<?php

namespace App\Domains\Transactions;

use App\Domains\Accounts\Account;
use App\Domains\Tags\Tag;
use App\Domains\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'date',
        'amount',
        'author_id',
        'fromable_type',
        'fromable_id',
        'toable_type',
        'toable_id',
        'parent_id',
        'note',
        'is_last',
    ];

    protected $casts = [
        'tag_ids' => 'array',
    ];

    protected $appends = [
        'type',
        'from',
        'to'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function fromable(): MorphTo
    {
        return $this->morphTo('fromable');
    }

    public function toable(): MorphTo
    {
        return $this->morphTo('toable');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getTypeAttribute(): string
    {
        if ($this->fromable_type === Account::class && $this->toable_type === Account::class) {
            return 'transfer';
        } elseif ($this->fromable_type === Account::class) {
            return 'expense';
        } else {
            return 'income';
        }
    }

    public function getFromAttribute(): mixed
    {
        return $this->fromable;
    }

    public function getToAttribute(): mixed
    {
        return $this->toable;
    }
}
