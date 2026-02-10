<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-migrations', function () {
    \Artisan::call('migrate', ['--force' => true]);
    \Artisan::call('db:seed', ['--force' => true]);
    return "Migraciones ejecutadas";
});

