<?php

namespace App\Domains\Tags;

use App\Domains\Vendors\Vendor;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Transactions\Transaction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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

    public function transactions(): MorphToMany
    {
        return $this->morphedByMany(Transaction::class, 'taggable');
    }

    public function vendors(): MorphToMany
    {
        return $this->morphedByMany(Vendor::class, 'taggable');
    }
}
