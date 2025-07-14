<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoStatusProposta extends Model
{
    use HasFactory;

    protected $table = 'historico_status_proposta';

    protected $fillable = [
        'proposta_id',
        'status',
        'data_status',
        'observacao'
    ];

    public function proposta()
    {
        return $this->belongsTo(PropostaCurso::class, 'proposta_id');
    }
}
