<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\GuardController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\VisitController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\RedirectToResidences;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware('auth', RedirectToResidences::class)->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware('auth', RedirectToResidences::class)->name('dashboard');

Route::middleware('auth')->group(function () {
    // Dashboard y perfil
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
    Route::get('/residences/{id}', [ResidenceController::class, 'show'])->name('residences.show');
    Route::get('/residences/edit/{id}', [ResidenceController::class, 'edit'])->name('residences.edit');
    Route::put('/residences/{id}', [ResidenceController::class, 'update'])->name('residences.update');
    Route::delete('/residences/{id}', [ResidenceController::class, 'destroy'])->name('residences.destroy');
    //Route::get('/residences/{residence}/buildings', [ResidenceController::class, 'getBuildingsByResidence'])
    //->name('residences.buildings');

    // Edificios
    Route::get('/buildings', [BuildingController::class, 'index'])->name('buildings.index');
    Route::get('/residences/{residenceId}/buildings', [BuildingController::class, 'indexByResidence'])->name('buildings.indexByResidence');
    Route::post('/buildings', [BuildingController::class, 'store'])->name('buildings.store');
    Route::get('/buildings/create', [BuildingController::class, 'create'])->name('buildings.create');
    Route::put('/buildings/{id}', [BuildingController::class, 'update'])->name('buildings.update');
    Route::delete('/buildings/{id}', [BuildingController::class, 'destroy'])->name('buildings.destroy');

    // Rutas para vigilantes
    Route::get('/guards', [GuardController::class, 'index'])->name('guards.index');
    Route::get('/residences/{residenceId}/guards', [GuardController::class, 'indexByResidence'])->name('guards.indexByResidence');
    Route::get('/guards/create', [GuardController::class, 'create'])->name('guards.create');
    Route::post('/guards', [GuardController::class, 'store'])->name('guards.store');
    Route::get('/guards/{id}', [GuardController::class, 'edit'])->name('guards.edit');
    Route::put('/guards/{id}', [GuardController::class, 'update'])->name('guards.update');
    Route::delete('/guards/{id}', [GuardController::class, 'destroy'])->name('guards.destroy');

    // Departamentos
    Route::get('/apartments', [ApartmentController::class, 'index'])->name('apartments.index');
    Route::get('/residences/{residenceId}/apartments', [ApartmentController::class, 'indexByResidence'])->name('apartments.indexByResidence');
    Route::get('/apartments/create', [ApartmentController::class, 'create'])->name('apartments.create');
    Route::post('/apartments', [ApartmentController::class, 'store'])->name('apartments.store');
    Route::get('/apartments/{id}', [ApartmentController::class, 'edit'])->name('apartments.edit');
    Route::put('/apartments/{id}', [ApartmentController::class, 'update'])->name('apartments.update');
    Route::delete('/apartments/{id}', [ApartmentController::class, 'destroy'])->name('apartments.destroy');

    // Visitantes
    Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
    Route::get('/residences/{residenceId}/visitors', [VisitorController::class, 'indexByResidence'])->name('visitors.indexByResidence');

    // Visitas
    Route::get('/visits', [VisitController::class, 'index'])->name('visits.index');
    Route::get('/residences/{residenceId}/visits', [VisitController::class, 'indexByResidence'])->name('visits.indexByResidence');
});

// Rutas de autenticación generadas por Breeze
require __DIR__.'/auth.php';
