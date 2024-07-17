<?php

namespace App\Domains\Donors;

use App\Domains\Transactions\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Donor extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'occupation',
        'title',
        'company',
    ];

    public function donations(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'fromable');
    }
}
