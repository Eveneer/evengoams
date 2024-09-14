<?php

declare(strict_types=1);

use App\Domains\Users\Actions\Queries\GetUser;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('users')->group(function (): void {
    Route::get('/{id}', GetUser::class);
});