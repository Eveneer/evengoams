<?php

namespace App\Domains\Pledges;

use App\Domains\Donors\Donor;
use App\Domains\Pledges\Enums\PledgeRecursEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pledge extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'donor_id',
        'amount',
        'recurs',
        'due_date',
    ];

    protected $casts = [
        'recurs' => PledgeRecursEnum::class,
        'due_date' => 'date',
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }
}