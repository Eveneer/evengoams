<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/domains/accounts.php';
require __DIR__ . '/domains/donors.php';
require __DIR__ . '/domains/transactions.php';
require __DIR__ . '/domains/vendors.php';
require __DIR__ . '/domains/users.php';
require __DIR__ . '/domains/employees.php';
require __DIR__ . '/domains/revenuestreams.php';
