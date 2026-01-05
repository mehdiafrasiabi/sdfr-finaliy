<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    require  __DIR__ . '/admin.php';
});
Route::prefix('manager')->group(function () {
    require  __DIR__ . '/manager.php';
});
Route::get('/test-error/{code}', function ($code) {
    abort($code);
});

require  __DIR__ . '/client.php';

