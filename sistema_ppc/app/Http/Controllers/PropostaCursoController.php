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
        // Validação e criação da proposta
        try {
            $validated = $request->validated();
            

            $proposta = PropostaCurso::create([
                'nome' => $validated['nome'],
                'carga_horaria_total' => $validated['carga_horaria_total'],
                'quantidade_semestres' => $validated['quantidade_semestres'],
                'justificativa' => $validated['justificativa'],
                'impacto_social' => $validated['impacto_social'],
                'status' => 'submitted',
                'autor_id' => auth()->id() ?? 1,
            ]);

            if (!empty($validated['disciplinas'])) {
                $proposta->disciplinas()->createMany($validated['disciplinas']);
            }

            return response()->json([
                'mensagem' => 'Proposta criada com sucesso!',
                'proposta' => $proposta->load('disciplinas')
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'erro' => 'Erro ao criar proposta',
                'mensagem' => $e->getMessage(),
            ], 422);
        }
    }


    // Avaliação da proposta (avaliador)
    public function avaliar(AvaliarPropostaCursoRequest $request, $id)
    {
        $proposta = PropostaCurso::findOrFail($id);
        $validated = $request->validated();

        $proposta->avaliador_id = auth()->id();
        $proposta->comentario_avaliador = $validated['comentario'];

        if ($validated['acao'] === 'retornar') {
            $proposta->status = 'mudanças_requeridas';
        } elseif ($validated['acao'] === 'encaminhar') {
            $proposta->status = 'em_aprovacao';
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
        $proposta->comentario_decisor = $validated['comentario_decisor'];

        if ($validated['decisao'] === 'aprovar') {
            $proposta->status = 'approved';
        } elseif ($validated['decisao'] === 'reprovar') {
            $proposta->status = 'rejected';
        }

        $proposta->save();

        return response()->json([
            'mensagem' => 'Decisão registrada com sucesso.',
            'proposta' => $proposta
        ]);
    }
    public function indexUsuario(Request $request)
    {
        $usuario = $request->user();

        $propostas = PropostaCurso::with('disciplinas') // ou outras relações se quiser
            ->where('autor_id', $usuario->id)
            ->get();

        return response()->json($propostas);
    }
}
