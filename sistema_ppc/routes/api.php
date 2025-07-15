<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropostaCursoController;

//Rotas públicas (qualquer usuário pode ver)
Route::get('/propostas', [PropostaCursoController::class, 'index']);
Route::get('/propostas/{id}', [PropostaCursoController::class, 'show']);

// Somente submissor pode criar propostas
Route::middleware(['auth:sanctum', 'role:submissor'])->group(function () {
    Route::post('/propostas', [PropostaCursoController::class, 'store']);
});

// Somente avaliador pode avaliar propostas
Route::middleware(['auth:sanctum', 'role:avaliador'])->group(function () {
    Route::put('/propostas/{id}/avaliar', [PropostaCursoController::class, 'avaliar']);
});

// Somente decisor pode aprovar ou rejeitar
Route::middleware(['auth:sanctum', 'role:decisor'])->group(function () {
    Route::put('/propostas/{id}/decidir', [PropostaCursoController::class, 'decidir']);
});
