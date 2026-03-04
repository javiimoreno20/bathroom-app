<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BathroomPermissionController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\admin\ImportController;
use App\Http\Controllers\admin\AlumnController;
use App\Http\Controllers\admin\TeacherController;

//Cuando se inicia la app se redirige automáticamente al formulario de inicio de sesión o a la página de creación de permisos... según si está logueado o no.
Route::get('/', function () {
    return session()->has('teacher_id') ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::middleware('ProfesorAuth')->group(function () {
    Route::get('/dashboard', [BathroomPermissionController::class, 'index'])->name('dashboard');

    Route::post('/give-permission', [BathroomPermissionController::class, 'givePermission'])->name('give.permission');

    Route::post('/mark-returned/{id}', [BathroomPermissionController::class, 'markReturned'])->name('mark.returned');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::post('/import/{type}', [ImportController::class, 'import'])->name('import');

    // -------- ALUMNOS --------

    Route::get('/alumns', [AlumnController::class, 'index'])->name('alumns.index');

    Route::get('/alumns/create', [AlumnController::class, 'create'])->name('alumns.create');

    Route::post('/alumns', [AlumnController::class, 'store'])->name('alumns.store');

    Route::get('/alumns/{id}/edit', [AlumnController::class, 'edit'])->name('alumns.edit');

    Route::put('/alumns/{id}', [AlumnController::class, 'update'])->name('alumns.update');
    Route::patch('/alumns/{id}', [AlumnController::class, 'update']);

    Route::delete('/alumns/{id}', [AlumnController::class, 'destroy'])->name('alumns.destroy');

    // -------- PROFESORES --------

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');

    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');

    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');

    Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');

    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::patch('/teachers/{id}', [TeacherController::class, 'update']);

    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
});

// routes/web.php
use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache-temp', function() {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
    return "¡Caché de Laravel limpiada!";
});

