<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

require __DIR__ . '/domains/accounts.php';
require __DIR__ . '/domains/donors.php';
require __DIR__ . '/domains/employees.php';
require __DIR__ . '/domains/pledges.php';
require __DIR__ . '/domains/revenuestreams.php';
require __DIR__ . '/domains/revenuestreamtypes.php';
require __DIR__ . '/domains/tags.php';
require __DIR__ . '/domains/transactions.php';
require __DIR__ . '/domains/vendors.php';
require __DIR__ . '/domains/users.php';
