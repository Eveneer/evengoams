<?php

declare(strict_types=1);

use App\Domains\RevenueStreams\Actions\Queries\GetRevenueStream;
use App\Domains\RevenueStreams\Actions\Queries\GetRevenueStreams;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('revenuestreams')->group(function (): void {
    Route::get('/', GetRevenueStreams::class);
    Route::get('/{id}', GetRevenueStream::class);
});