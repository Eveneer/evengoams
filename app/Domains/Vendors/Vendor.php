<?php

namespace App\Domains\Vendors;

use App\Domains\Tags\Tag;
use App\Domains\Transactions\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Vendor extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'type',
        'contacts',
    ];

    protected $casts = [
        'contacts' => 'array',
    ];

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'toable');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
