<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['erro' => 'Não autenticado.'], 401);
        }

        if (!in_array($user->tipo, $roles)) {
            return response()->json(['erro' => 'Acesso não autorizado.'], 403);
        }

        return $next($request);
    }
}
