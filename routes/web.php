<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/domains/accounts.php';
require __DIR__ . '/domains/donors.php';
require __DIR__ . '/domains/transactions.php';
require __DIR__ . '/domains/revenuestreamtypes.php';