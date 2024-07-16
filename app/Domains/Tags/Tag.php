<?php

namespace App\Domains\Tags;

use App\Domains\Vendors\Vendor;
use App\Domains\Transactions\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'key',
        'model',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function transactions()
    {
        return $this->morphedByMany(Transaction::class, 'taggable');
    }

    public function vendors()
    {
        return $this->morphedByMany(Vendor::class, 'taggable');
    }
}
