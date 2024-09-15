<?php

declare(strict_types=1);

use App\Domains\RevenueStreamTypes\Actions\Queries\GetRevenueStreamType;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('revenuestreamtypes')->group(function (): void {
    Route::get('/{id}', GetRevenueStreamType::class);
});