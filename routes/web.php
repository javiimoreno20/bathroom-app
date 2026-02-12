<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BathroomPermissionController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BathroomPermissionController::class, 'index'])->name('dashboard');

    Route::post('/give-permission', [BathroomPermissionController::class, 'givePermission'])->name('give.permission');
    
    Route::post('/mark-returned/{id}', [BathroomPermissionController::class, 'markReturned'])->name('mark.returned');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
