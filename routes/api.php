<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResidenceController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Autenticación
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Residences (API)
Route::middleware('auth:sanctum')->group(function () {


    Route::get('/managers', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);



    Route::get('/residences', [ResidenceController::class, 'index']);
    Route::post('/residences', [ResidenceController::class, 'store']);
    Route::get('/residences/{id}', [ResidenceController::class, 'show']);
    Route::put('/residences/{id}', [ResidenceController::class, 'update']);
    Route::delete('/residences/{id}', [ResidenceController::class, 'destroy']);
});
