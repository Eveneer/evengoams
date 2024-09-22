<?php

namespace App\Domains\RevenueStreams;

use App\Domains\RevenueStreamTypes\RevenueStreamType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RevenueStream extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type_id',
        'values',
    ];

    protected $casts = [
        'values' => 'array',
    ];
<<<<<<< HEAD
}
=======

    public function type(): BelongsTo
    {
        return $this->belongsTo(RevenueStreamType::class, 'type_id');
    }

}
>>>>>>> main
