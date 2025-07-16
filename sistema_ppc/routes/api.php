<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropostaCursoController;

// Rotas públicas
Route::get('/propostas', [PropostaCursoController::class, 'index']);
Route::get('/propostas/{id}', [PropostaCursoController::class, 'show']);

// Autenticadas por papel
Route::middleware(['auth:sanctum', 'role:submissor'])->group(function () {
    Route::post('/propostas', [PropostaCursoController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'role:avaliador'])->group(function () {
    Route::put('/propostas/{id}/avaliar', [PropostaCursoController::class, 'avaliar']);
});

Route::middleware(['auth:sanctum', 'role:decisor'])->group(function () {
    Route::put('/propostas/{id}/decidir', [PropostaCursoController::class, 'decidir']);
});

// Login
Route::post('/login', [AuthController::class, 'login']);
