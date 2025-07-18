<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DecidirPropostaCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comentario_decisor' => 'nullable|string',
            'decisao' => 'required|in:aprovar,reprovar',
        ];
    }
}
