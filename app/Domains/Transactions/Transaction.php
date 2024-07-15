<?php

namespace App\Domains\Transactions;

use App\Domains\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function fromable()
    {
        return $this->morphTo();
    }

    public function toable()
    {
        return $this->morphTo();
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
