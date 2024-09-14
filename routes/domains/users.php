<?php

declare(strict_types=1);

use App\Domains\Users\Actions\Queries\GetUser;
use App\Domains\Users\Actions\Queries\GetUsers;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('users')->group(function (): void {
    Route::get('/', GetUsers::class);
    Route::get('/{id}', GetUser::class);

});