<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

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
