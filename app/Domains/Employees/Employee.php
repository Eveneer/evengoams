<?php

namespace App\Domains\Employees;

use App\Domains\Transactions\Transaction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'toable');
    }
}
