<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
        public function handle($request, \Closure $next, ...$tiposPermitidos)
    {
        $usuario = auth()->user();

        if (!$usuario || !in_array($usuario->tipo, $tiposPermitidos)) {
            return response()->json(['erro' => 'Acesso não autorizado.'], 403);
        }

        return $next($request);
    }

}
