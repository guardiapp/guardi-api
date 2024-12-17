<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\GuardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    // Dashboard y perfil
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para usuarios generales
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
//});

//Route::middleware(['auth', 'checkUserType:Admin'])->group(function () {
    // Rutas para managers
    Route::get('/managers', [ManagerController::class, 'index'])->name('managers.index');
    Route::get('/managers/{id}', [ManagerController::class, 'edit'])->name('managers.edit');
    Route::put('/managers/{id}', [ManagerController::class, 'update'])->name('managers.update');
    Route::delete('/managers/{id}', [ManagerController::class, 'destroy'])->name('managers.destroy');

    // Rutas para vigilantes
    Route::get('/guards', [GuardController::class, 'index'])->name('guards.index');
    Route::get('/guards/{id}', [GuardController::class, 'edit'])->name('guards.edit');
    Route::put('/guards/{id}', [GuardController::class, 'update'])->name('guards.update');
    Route::delete('/guards/{id}', [GuardController::class, 'destroy'])->name('guards.destroy');




//});

//Route::middleware(['auth', 'checkUserType:Admin,Manager'])->group(function () {
    // Residences (Web)
    Route::get('/residences', [ResidenceController::class, 'index'])->name('residences.index');
    Route::get('/residences/create', [ResidenceController::class, 'create'])->name('residences.create');
    Route::post('/residences', [ResidenceController::class, 'store'])->name('residences.store');
    Route::get('/residences/{id}', [ResidenceController::class, 'edit'])->name('residences.edit');
    Route::put('/residences/{id}', [ResidenceController::class, 'update'])->name('residences.update');
    Route::delete('/residences/{id}', [ResidenceController::class, 'destroy'])->name('residences.destroy');
});

// // Ruta pública para la página principal
// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['verified'])->name('home');


// Rutas de autenticación generadas por Breeze
require __DIR__.'/auth.php';
