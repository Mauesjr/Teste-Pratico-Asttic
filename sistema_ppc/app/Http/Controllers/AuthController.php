<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required'
        ]);

        // Busca o usuário pelo email
        $user = Usuario::where('email', $request->email)->first();

        // Verifica se encontrou e se a senha está correta
        if (!$user || !\Hash::check($request->senha, $user->senha)) {
            return response()->json(['erro' => 'Credenciais inválidas'], 401);
        }

        // Gera o token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'mensagem' => 'Login realizado com sucesso.',
            'token' => $token,
            'usuario' => $user
        ]);
    }
}
