<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropostaCursoController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
Route::get('/propostas', [PropostaCursoController::class, 'index']); // Listar todas as propostas
Route::get('/propostas/{id}', [PropostaCursoController::class, 'show']); // Detalhes da proposta

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas - Submissor
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:submissor'])->group(function () {
    Route::post('/propostas', [PropostaCursoController::class, 'store']); // Criar proposta
    Route::get('/minhas-propostas', [PropostaCursoController::class, 'indexUsuario']); // Propostas do usuário
    Route::put('/propostas/{id}/corrigir', [PropostaCursoController::class, 'corrigir']); // Submissor corrige proposta
    Route::get('/propostas/{id}/corrigir', [PropostaCursoController::class, 'formularioCorrecao']); // Carregar proposta p/ corrigir
});

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas - Avaliador
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:avaliador'])->group(function () {
    Route::get('/propostas-para-avaliar', [PropostaCursoController::class, 'propostasParaAvaliar']);
    Route::put('/propostas/{id}/avaliar', [PropostaCursoController::class, 'avaliar']);
});

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas - Decisor
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:decisor'])->group(function () {
    Route::get('/propostas-para-decisao', [PropostaCursoController::class, 'propostasParaDecisao']);
    Route::put('/propostas/{id}/decidir', [PropostaCursoController::class, 'decidir']);
    Route::get('/propostas/pendentes-decisao', [PropostaCursoController::class, 'listarPendentesDecisao']);
});
