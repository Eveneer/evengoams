<?php

declare(strict_types=1);

use App\Domains\RevenueStreams\Actions\CreateRevenueStream;
use App\Domains\RevenueStreams\Actions\Queries\GetRevenueStream;
use App\Domains\RevenueStreams\Actions\Queries\GetRevenueStreams;
use App\Domains\RevenueStreams\Actions\RestoreRevenueStream;
use App\Domains\RevenueStreams\Actions\TrashRevenueStream;
use App\Domains\RevenueStreams\Actions\UpdateRevenueStream;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('revenuestreams')->group(function (): void {
    Route::get('/', GetRevenueStreams::class);
    Route::get('/{id}', GetRevenueStream::class);
    Route::post('/', CreateRevenueStream::class);
    Route::put('/{id}', UpdateRevenueStream::class);
    Route::post('/{id}/trash', TrashRevenueStream::class);
    Route::post('/{id}/restore', RestoreRevenueStream::class);
});