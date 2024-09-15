<?php

declare(strict_types=1);

use App\Domains\Accounts\Actions\Queries\GetAccount;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('accounts')->group(function (): void {
    Route::get('/{id}', GetAccount::class);
});