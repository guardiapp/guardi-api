<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidenceController;
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

    // Residences (Web)
    Route::get('/residences', [ResidenceController::class, 'index'])->name('residences.index');
    Route::get('/residences/create', [ResidenceController::class, 'create'])->name('residences.create');
    Route::post('/residences', [ResidenceController::class, 'store'])->name('residences.store');
    Route::get('/residences/{id}', [ResidenceController::class, 'edit'])->name('residences.edit');
    Route::put('/residences/{id}', [ResidenceController::class, 'update'])->name('residences.update');
    Route::delete('/residences/{id}', [ResidenceController::class, 'destroy'])->name('residences.destroy');
});

// Ruta pública para la página principal
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
