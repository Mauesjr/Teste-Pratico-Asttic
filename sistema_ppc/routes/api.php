<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropostaCursoController;

// Rotas públicas
Route::get('/propostas', [PropostaCursoController::class, 'index']); // todas as propostas
Route::get('/propostas/{id}', [PropostaCursoController::class, 'show']); // detalhe

// Submissor autenticado
Route::middleware(['auth:sanctum', 'role:submissor'])->group(function () {
    Route::post('/propostas', [PropostaCursoController::class, 'store']);
    Route::get('/minhas-propostas', [PropostaCursoController::class, 'indexUsuario']); // ✅ propostas do próprio usuário
});

// Avaliador autenticado
Route::middleware(['auth:sanctum', 'role:avaliador'])->group(function () {
    Route::put('/propostas/{id}/avaliar', [PropostaCursoController::class, 'avaliar']);
    Route::get('/propostas-para-avaliar', [PropostaCursoController::class, 'propostasParaAvaliar']); // ✅ nova rota etapa 2
});

// Decisor autenticado
Route::middleware(['auth:sanctum', 'role:decisor'])->group(function () {
    Route::put('/propostas/{id}/decidir', [PropostaCursoController::class, 'decidir']);
});

// Login
Route::post('/login', [AuthController::class, 'login']);
