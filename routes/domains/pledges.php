<?php

declare(strict_types=1);

use App\Domains\Pledges\Actions\Queries\GetPledge;
use App\Domains\Pledges\Actions\Queries\GetPledges;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('pledges')->group(function (): void {
    Route::get('/', GetPledges::class);
    Route::get('/{id}', GetPledge::class);
});