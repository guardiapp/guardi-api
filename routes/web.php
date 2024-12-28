<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\GuardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    // Dashboard y perfil
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    //})->middleware(['verified'])->name('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // managers
    Route::get('/managers', [ManagerController::class, 'index'])->name('managers.index');
    Route::get('/managers/create', [ManagerController::class, 'create'])->name('managers.create');
    Route::post('/managers', [ManagerController::class, 'store'])->name('managers.store');
    Route::get('/managers/{id}', [ManagerController::class, 'edit'])->name('managers.edit');
    Route::put('/managers/{id}', [ManagerController::class, 'update'])->name('managers.update');
    Route::delete('/managers/{id}', [ManagerController::class, 'destroy'])->name('managers.destroy');
    Route::get('/managers/{manager}/residences', [ManagerController::class, 'getResidencesByManager'])
    ->name('managers.residences');

    // Residencias
    Route::get('/residences', [ResidenceController::class, 'index'])->name('residences.index');
    Route::get('/residences/create', [ResidenceController::class, 'create'])->name('residences.create');
    Route::post('/residences', [ResidenceController::class, 'store'])->name('residences.store');
    Route::get('/residences/{id}', [ResidenceController::class, 'edit'])->name('residences.edit');
    Route::put('/residences/{id}', [ResidenceController::class, 'update'])->name('residences.update');
    Route::delete('/residences/{id}', [ResidenceController::class, 'destroy'])->name('residences.destroy');
    Route::get('/residences/{residence}/buildings', [ResidenceController::class, 'getBuildingsByResidence'])
    ->name('residences.buildings');

    // Edificios
    Route::post('/buildings', [BuildingController::class, 'store'])->name('buildings.store');
    Route::delete('/buildings/{id}', [BuildingController::class, 'destroy'])->name('buildings.destroy');

    // Rutas para vigilantes
    Route::get('/guards', [GuardController::class, 'index'])->name('guards.index');
    Route::get('/guards/create', [GuardController::class, 'create'])->name('guards.create');
    Route::post('/guards', [GuardController::class, 'store'])->name('guards.store');
    Route::get('/guards/{id}', [GuardController::class, 'edit'])->name('guards.edit');
    Route::put('/guards/{id}', [GuardController::class, 'update'])->name('guards.update');
    Route::delete('/guards/{id}', [GuardController::class, 'destroy'])->name('guards.destroy');

    // Residencias
    Route::get('/residents', [ResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/create', [ResidentController::class, 'create'])->name('residents.create');
    Route::post('/residents', [ResidentController::class, 'store'])->name('residents.store');
    Route::get('/residents/{id}', [ResidentController::class, 'edit'])->name('residents.edit');
    Route::put('/residents/{id}', [ResidentController::class, 'update'])->name('residents.update');
    Route::delete('/residents/{id}', [ResidentController::class, 'destroy'])->name('residents.destroy');

    // Visitantes
    Route::get('/visitors', [ResidenceController::class, 'index'])->name('residences.index');

    // Visitas
    Route::get('/visits', [ResidenceController::class, 'index'])->name('residences.index');

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
