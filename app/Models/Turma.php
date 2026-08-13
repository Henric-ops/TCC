<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $fillable = ['escola_id', 'nome', 'ano', 'periodo'];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function professores()
    {
        return $this->belongsToMany(Usuario::class, 'turma_professor');
    }

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'turma_aluno');
    }
}
