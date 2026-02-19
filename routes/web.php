<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BathroomPermissionController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\admin\ImportController;

//Cuando se inicia la app se redirige automáticamente al formulario de inicio de sesión o a la página de creación de permisos... según si está logueado o no.
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BathroomPermissionController::class, 'index'])->name('dashboard');

    Route::post('/give-permission', [BathroomPermissionController::class, 'givePermission'])->name('give.permission');

    Route::post('/mark-returned/{id}', [BathroomPermissionController::class, 'markReturned'])->name('mark.returned');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::post('/import/{type}', [ImportController::class, 'import'])->name('import');
});

