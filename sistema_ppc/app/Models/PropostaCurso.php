<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropostaCurso extends Model
{
    use HasFactory;
    protected $table = 'propostas_curso';

    protected $fillable = [
        'nome',
        'carga_horaria_total',
        'quantidade_semestres',
        'justificativa',
        'impacto_social',
        'comentario_avaliador',
        'comentario_decisor',
        'autor_id',
        'avaliador_id',
        'decisor_final_id',
        'status'
    ];
    public $timestamps = false;

    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class, 'curso_id');
    }

    public function historicoStatus()
    {
        return $this->hasMany(HistoricoStatusProposta::class, 'proposta_id');
    }

    public function autor()
    {
        return $this->belongsTo(Usuario::class, 'autor_id');
    }

    public function avaliador()
    {
        return $this->belongsTo(Usuario::class, 'avaliador_id');
    }

    public function decisorFinal()
    {
        return $this->belongsTo(Usuario::class, 'decisor_final_id');
    }
}
