<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvaliarPropostaCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comentarios_avaliador' => 'required|string',
            'acao' => 'required|in:retornar,encaminhar',
        ];
    }
}
