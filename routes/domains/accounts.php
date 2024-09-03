<?php

declare(strict_types=1);

use App\Domains\Accounts\Actions\CreateAccount;
use App\Domains\Accounts\Actions\Queries\GetAccount;
use App\Domains\Accounts\Actions\Queries\GetAccounts;
use App\Domains\Accounts\Actions\RestoreAccount;
use App\Domains\Accounts\Actions\TrashAccount;
use App\Domains\Accounts\Actions\UpdateAccount;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('accounts')->group(function (): void {
    Route::get('/', GetAccounts::class);
    Route::get('/{id}', GetAccount::class);
    Route::post('/create', CreateAccount::class);
    Route::put('/update', UpdateAccount::class);
    Route::post('/{id}/trash', TrashAccount::class);
    Route::post('/{id}/restore', RestoreAccount::class);
});