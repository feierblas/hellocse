<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('profils', [ProfilController::class, 'store']);
    Route::put('profils/{id}', [ProfilController::class, 'update']);
    Route::post('profils/{id}', [ProfilController::class, 'updateWithPost']);
    Route::delete('profils/{id}', [ProfilController::class, 'destroy']);
});

Route::get('profils', [ProfilController::class, 'index']);