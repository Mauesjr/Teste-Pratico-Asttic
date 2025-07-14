<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'tipo'
    ];

    public function propostasCriadas()
    {
        return $this->hasMany(PropostaCurso::class, 'autor_id');
    }

    public function propostasAvaliadas()
    {
        return $this->hasMany(PropostaCurso::class, 'avaliador_id');
    }

    public function propostasDecididas()
    {
        return $this->hasMany(PropostaCurso::class, 'decisor_final_id');
    }
}
