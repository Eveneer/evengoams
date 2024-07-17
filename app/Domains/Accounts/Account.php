<?php

namespace App\Domains\Accounts;

use App\Domains\Transactions\Transaction;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

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

    public function expenses(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'fromable');
    }

    public function incomes(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'toable');
    }

    public function transactions(): Builder
    {
        return DB::table('transactions')
            ->where(function (Builder $query) {
                $query->where('fromable_type', Account::class)
                    ->where('fromable_id', $this->id);
            })->orWhere(function (Builder $query) {
                $query->where('toable_type', Account::class)
                    ->where('toable_id', $this->id);
            });
    }
}
