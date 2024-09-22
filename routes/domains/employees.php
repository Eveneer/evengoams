<?php

declare(strict_types=1);

<<<<<<< HEAD
use App\Domains\Employees\Actions\CreateEmployee;
use App\Domains\Employees\Actions\RestoreEmployee;
use App\Domains\Employees\Actions\TrashEmployee;
use App\Domains\Employees\Actions\UpdateEmployee;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('employees')->group(function (): void {
    Route::post('/', CreateEmployee::class);
    Route::put('/{id}', UpdateEmployee::class);
    Route::post('/{id}/trash', TrashEmployee::class);
    Route::post('/{id}/restore', RestoreEmployee::class);
=======
use App\Domains\Employees\Actions\Queries\GetEmployee;
use App\Domains\Employees\Actions\Queries\GetEmployees;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('employees')->group(function (): void {
    Route::get('/', GetEmployees::class);
    Route::get('/{id}', GetEmployee::class);
>>>>>>> main
});