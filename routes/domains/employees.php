<?php

declare(strict_types=1);

use App\Domains\Employees\Actions\Queries\GetEmployee;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('employees')->group(function (): void {
    Route::get('/{id}', GetEmployee::class);
});