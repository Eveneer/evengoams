<?php

declare(strict_types=1);

use App\Domains\Tags\Actions\CreateTag;
use App\Domains\Tags\Actions\Queries\GetTag;
use App\Domains\Tags\Actions\Queries\GetTags;
use App\Domains\Tags\Actions\RestoreTag;
use App\Domains\Tags\Actions\TrashTag;
use App\Domains\Tags\Actions\UpdateTag;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('tags')->group(function (): void {
    Route::get('/', GetTags::class);
    Route::get('/{id}', GetTag::class);
    Route::post('/', CreateTag::class);
    Route::put('/{id}', UpdateTag::class);
    Route::post('/{id}/trash', TrashTag::class);
    Route::post('/{id}/restore', RestoreTag::class);
});