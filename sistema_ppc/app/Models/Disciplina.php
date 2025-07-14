<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $table = 'disciplinas';

    protected $fillable = [
        'curso_id',
        'nome',
        'carga_horaria',
        'semestre'
    ];

    public function curso()
    {
        return $this->belongsTo(PropostaCurso::class, 'curso_id');
    }
}
