<?php

namespace App\Domains\Vendors;

use App\Domains\Transactions\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'toable');
    }
}
