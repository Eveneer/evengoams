<?php

namespace App\Domains\Tags;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'key',
        'model',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
