<?php

use App\Http\Controllers\api\VisitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResidenceController;
use App\Http\Controllers\Api\ResidentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VisitController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Autenticación
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('refresh-token', [AuthController::class, 'refreshToken']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Residences (API)
Route::middleware('auth:api')->group(function () {


    //Route::get('/managers', [UserController::class, 'index']);
    //Route::get('/users/{id}', [UserController::class, 'show']);



    Route::get('/residences', [ResidenceController::class, 'index']);
    Route::post('/residences', [ResidenceController::class, 'store']);
    Route::get('/residences/{id}', [ResidenceController::class, 'show']);
    Route::put('/residences/{id}', [ResidenceController::class, 'update']);
    Route::delete('/residences/{id}', [ResidenceController::class, 'destroy']);


    // Route::get('/residents', [ResidentController::class, 'index']);
    // Route::post('/residents', [ResidentController::class, 'store']);
    // Route::get('/residents/{id}', [ResidentController::class, 'show']);
    // Route::put('/residents/{id}', [ResidentController::class, 'update']);
    // Route::delete('/residents/{id}', [ResidentController::class, 'destroy']);
    Route::apiResource('residents', ResidentController::class);
    Route::apiResource('visits', VisitController::class);
    Route::apiResource('visitors', VisitorController::class);



});
