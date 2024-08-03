<?php

namespace App\Domains\RevenueStreamTypes;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueStreamType extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];
}
