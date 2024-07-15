<?php

namespace App\Domains\Accounts;

use App\Domains\Transactions\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'balance',
        'type',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function fromableTransactions()
    {
        return $this->morphMany(Transaction::class, 'fromable');
    }

    public function toableTransactions()
    {
        return $this->morphMany(Transaction::class, 'toable');
    }
}
