<?php

declare(strict_types=1);

use App\Domains\RevenueStreamTypes\Actions\Queries\GetRevenueStreamType;
use App\Domains\RevenueStreamTypes\Actions\Queries\GetRevenueStreamTypes;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('revenuestreamtypes')->group(function (): void {
    Route::get('/', GetRevenueStreamTypes::class);
    Route::get('/{id}', GetRevenueStreamType::class);
});