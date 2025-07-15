<?php

namespace App\Http\Controllers;

use App\Models\PropostaCurso;
use Illuminate\Http\Request;
use App\Http\Requests\StorePropostaCursoRequest;
use App\Http\Requests\AvaliarPropostaCursoRequest;
use App\Http\Requests\DecidirPropostaCursoRequest;

class PropostaCursoController extends Controller
{
    // Listar todas as propostas com relações
    public function index()
    {
        $propostas = PropostaCurso::with('disciplinas', 'autor', 'avaliador', 'decisorFinal')->get();
        return response()->json($propostas);
    }

    // Exibir uma proposta específica
    public function show($id)
    {
        $proposta = PropostaCurso::with('disciplinas', 'autor', 'avaliador', 'decisorFinal')->findOrFail($id);
        return response()->json($proposta);
    }

    // Submeter nova proposta (submissor)
    public function store(StorePropostaCursoRequest $request)
    {
        $validated = $request->validated();

        $proposta = PropostaCurso::create([
            'nome' => $validated['nome'],
            'carga_horaria_total' => $validated['carga_horaria_total'],
            'quantidade_semestres' => $validated['quantidade_semestres'],
            'justificativa' => $validated['justificativa'],
            'impacto_social' => $validated['impacto_social'],
            'status' => 'submitted',
            'autor_id' => auth()->id() ?? 1, // fallback em testes
        ]);

        $proposta->disciplinas()->createMany($validated['disciplinas']);

        return response()->json([
            'mensagem' => 'Proposta criada com sucesso!',
            'proposta' => $proposta->load('disciplinas')
        ], 201);
    }

    // Avaliação da proposta (avaliador)
    public function avaliar(AvaliarPropostaCursoRequest $request, $id)
    {
        $proposta = PropostaCurso::findOrFail($id);
        $validated = $request->validated();

        $proposta->avaliador_id = auth()->id();
        $proposta->comentario_avaliador = $validated['comentario'];

        if ($validated['acao'] === 'retornar') {
            $proposta->status = 'changes_required';
        } elseif ($validated['acao'] === 'encaminhar') {
            $proposta->status = 'in_approval';
        }

        $proposta->save();

        return response()->json([
            'mensagem' => 'Proposta avaliada com sucesso.',
            'proposta' => $proposta
        ]);
    }

    // Decisão final (decisor)
    public function decidir(DecidirPropostaCursoRequest $request, $id)
    {
        $proposta = PropostaCurso::findOrFail($id);
        $validated = $request->validated();

        $proposta->decisor_final_id = auth()->id();
        $proposta->comentario_decisao_final = $validated['comentario'];

        if ($validated['decisao'] === 'aprovar') {
            $proposta->status = 'approved';
            $proposta->aprovado_em = now();
        } elseif ($validated['decisao'] === 'reprovar') {
            $proposta->status = 'rejected';
            $proposta->rejeitado_em = now();
        }

        $proposta->save();

        return response()->json([
            'mensagem' => 'Decisão registrada com sucesso.',
            'proposta' => $proposta
        ]);
    }
}
