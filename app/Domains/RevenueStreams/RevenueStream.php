<?php

namespace App\Domains\RevenueStreams;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Domains\RevenueStreamTypes\RevenueStreamType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RevenueStream extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'type_id',
        'values',
    ];

    protected $casts = [
        'values' => 'array',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(RevenueStreamType::class, 'type_id');
    }
}
