<?php

namespace App\Http\Controllers;

use App\Models\PropostaCurso;
use Illuminate\Http\Request;

class PropostaCursoController extends Controller
{
    // Listar todas as propostas com disciplinas e autores
    public function index()
    {
        $propostas = PropostaCurso::with('disciplinas', 'autor', 'avaliador', 'decisorFinal')->get();

        return response()->json($propostas);
    }

    // Exibir uma proposta específica com relações
    public function show($id)
    {
        $proposta = PropostaCurso::with('disciplinas', 'autor', 'avaliador', 'decisorFinal')->findOrFail($id);

        return response()->json($proposta);
    }

    // Submeter uma nova proposta (submissor)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'carga_horaria_total' => 'required|integer|min:1',
            'quantidade_semestres' => 'required|integer|min:1',
            'justificativa' => 'required|string',
            'impacto_social' => 'required|string',
            'disciplinas' => 'required|array|min:1',
            'disciplinas.*.nome' => 'required|string|max:255',
            'disciplinas.*.carga_horaria' => 'required|integer|min:1',
            'disciplinas.*.semestre' => 'required|integer|min:1',
        ]);

        // Criar a proposta
        $proposta = PropostaCurso::create([
            'nome' => $validated['nome'],
            'carga_horaria_total' => $validated['carga_horaria_total'],
            'quantidade_semestres' => $validated['quantidade_semestres'],
            'justificativa' => $validated['justificativa'],
            'impacto_social' => $validated['impacto_social'],
            'status' => 'submitted',
            'autor_id' => auth()->id() ?? 1, // Fallback para testes
        ]);

        // Criar disciplinas associadas
        $proposta->disciplinas()->createMany($validated['disciplinas']);

        return response()->json([
            'mensagem' => 'Proposta criada com sucesso!',
            'proposta' => $proposta->load('disciplinas')
        ], 201);
    }

    // Atualizar status ou adicionar comentários (avaliador e decisor)
    public function update(Request $request, $id)
    {
        $proposta = PropostaCurso::findOrFail($id);
        $user = auth()->user();

        $request->validate([
            'acao' => 'required|in:retornar,encaminhar,aprovar,reprovar',
            'comentario' => 'nullable|string'
        ]);

        switch ($request->acao) {
            case 'retornar':
                if ($user->tipo !== 'avaliador') {
                    return response()->json(['erro' => 'Acesso negado.'], 403);
                }
                $proposta->status = 'changes_required';
                $proposta->comentario_avaliador = $request->comentario;
                $proposta->avaliador_id = $user->id;
                break;

            case 'encaminhar':
                if ($user->tipo !== 'avaliador') {
                    return response()->json(['erro' => 'Acesso negado.'], 403);
                }
                $proposta->status = 'in_approval';
                $proposta->comentario_avaliador = $request->comentario;
                $proposta->avaliador_id = $user->id;
                break;

            case 'aprovar':
                if ($user->tipo !== 'decisor') {
                    return response()->json(['erro' => 'Acesso negado.'], 403);
                }
                $proposta->status = 'approved';
                $proposta->comentario_decisao_final = $request->comentario;
                $proposta->decisor_final_id = $user->id;
                $proposta->aprovado_em = now();
                break;

            case 'reprovar':
                if ($user->tipo !== 'decisor') {
                    return response()->json(['erro' => 'Acesso negado.'], 403);
                }
                $proposta->status = 'rejected';
                $proposta->comentario_decisao_final = $request->comentario;
                $proposta->decisor_final_id = $user->id;
                $proposta->rejeitado_em = now();
                break;
        }

        $proposta->save();

        return response()->json([
            'mensagem' => 'Status atualizado com sucesso.',
            'proposta' => $proposta
        ]);
    }

}
