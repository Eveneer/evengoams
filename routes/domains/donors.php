<?php

declare(strict_types=1);

use App\Domains\Donors\Actions\CreateDonor;
use App\Domains\Donors\Actions\RestoreDonor;
use App\Domains\Donors\Actions\TrashDonor;
use App\Domains\Donors\Actions\UpdateDonor;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('donors')->group(function (): void {
    Route::post('/', CreateDonor::class);
    Route::put('/{id}', UpdateDonor::class);
    Route::post('/{id}/trash', TrashDonor::class);
    Route::post('/{id}/restore', RestoreDonor::class);
});