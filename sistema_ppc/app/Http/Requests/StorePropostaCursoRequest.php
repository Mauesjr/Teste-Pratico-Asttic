<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropostaCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permite o uso da request
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'carga_horaria_total' => 'required|integer|min:1',
            'quantidade_semestres' => 'required|integer|min:1',
            'justificativa' => 'required|string',
            'impacto_social' => 'required|string',
            'disciplinas' => 'required|array|min:1',
            'disciplinas.*.nome' => 'required|string|max:255',
            'disciplinas.*.carga_horaria' => 'required|integer|min:1',
            'disciplinas.*.semestre' => 'required|integer|min:1',
        ];
    }
}
