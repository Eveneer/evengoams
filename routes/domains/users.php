<?php

declare(strict_types=1);

use App\Domains\Users\Actions\CreateUser;
use App\Domains\Users\Actions\RestoreUser;
use App\Domains\Users\Actions\TrashUser;
use App\Domains\Users\Actions\UpdateUser;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('users')->group(function (): void {
    Route::post('/', CreateUser::class);
    Route::put('/{id}', UpdateUser::class);
    Route::post('/{id}/trash', TrashUser::class);
    Route::post('/{id}/restore', RestoreUser::class);
});