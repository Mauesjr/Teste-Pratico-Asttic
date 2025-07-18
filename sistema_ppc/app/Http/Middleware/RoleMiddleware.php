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
            if ($request->expectsJson()) {
                return response()->json(['erro' => 'Não autenticado.'], 401);
        }

        abort(401, 'Não autenticado.');
        }

        if (!in_array(strtolower($user->tipo), array_map('strtolower', $roles))) {
            return response()->json(['erro' => 'Acesso não autorizado.'], 403);
        }

        return $next($request);
    }
    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            return null;
        }
        return null;
    }
}
