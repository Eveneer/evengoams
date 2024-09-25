<?php

declare(strict_types=1);

use App\Domains\RevenueStreamTypes\Actions\CreateRevenueStreamType;
use App\Domains\RevenueStreamTypes\Actions\RestoreRevenueStreamType;
use App\Domains\RevenueStreamTypes\Actions\TrashRevenueStreamType;
use App\Domains\RevenueStreamTypes\Actions\UpdateRevenueStreamType;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('revenuestreamtypes')->group(function (): void {
    Route::post('/', CreateRevenueStreamType::class);
    Route::put('/{id}', UpdateRevenueStreamType::class);
    Route::post('/{id}/trash', TrashRevenueStreamType::class);
    Route::post('/{id}/restore', RestoreRevenueStreamType::class);
});