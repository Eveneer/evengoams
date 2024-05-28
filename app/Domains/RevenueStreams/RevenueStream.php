<?php

namespace App\Domains\RevenueStreams;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueStream extends Model
{
    use HasFactory, HasUuids;
}
