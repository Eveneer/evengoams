<?php

namespace App\Domains\Donors;

use App\Domains\Transactions\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'fromable');
    }
}
