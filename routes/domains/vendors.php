<?php

declare(strict_types=1);

use App\Domains\Vendors\Actions\CreateVendor;
use App\Domains\Vendors\Actions\RestoreVendor;
use App\Domains\Vendors\Actions\TrashVendor;
use App\Domains\Vendors\Actions\UpdateVendor;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('vendors')->group(function (): void {
    Route::post('/', CreateVendor::class);
    Route::put('/{id}', UpdateVendor::class);
    Route::post('/{id}/trash', TrashVendor::class);
    Route::post('/{id}/restore', RestoreVendor::class);
});