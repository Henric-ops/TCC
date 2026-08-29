<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frequencia extends Model
{
    protected $fillable = ['aluno_id', 'turma_id', 'data', 'presente', 'justificativa', 'registrado_por'];

    protected $casts = [
        'data' => 'date',
        'presente' => 'boolean',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}