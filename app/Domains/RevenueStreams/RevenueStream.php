<?php

namespace App\Domains\RevenueStreams;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RevenueStream extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
}
